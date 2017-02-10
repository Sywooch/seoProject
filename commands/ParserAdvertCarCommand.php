<?php

abstract class ParserAdvertCarCommand extends CConsoleCommand
{
    const PARSER_ID_AMRU = 1;
    const PARSER_ID_NIZH = 2;
    const PARSER_ID_AGAT = 3;
    const PARSER_ID_PDC = 4;
    const PARSER_ID_BCR = 5;
    const PARSER_ID_CARCOPY = 6;
    const PARSER_ID_AUTO_LIFE = 7;
    const PARSER_ID_FORD = 8;
    const PARSER_ID_FRESH = 9;
    const PARSER_ID_ARTAN = 10;
    const PARSER_ID_AVTO_OBMEN = 11;
    const PARSER_ID_CRWL = 12;

    protected static $_actionsForLogging = [
        'parse',
        'hide'
    ];

    protected $_id;
    protected $_urlSearchPage;
    protected $_hideNotParsed = false;
    protected $_advertIdsForHide;
    protected $_defaultValuesForMapper;
    /**
     * @var ParserLog
     */
    protected $_log;
    /**
     * @var ParserAdvertCarMapper
     */
    protected $_mapper;

    private $_advertProcessCounter;
    private $_advertIdsForHideCountInBegin;

    public function beforeAction($action, $params)
    {
        if (parent::beforeAction($action, $params)) {
            if (in_array($action, static::$_actionsForLogging)) {
                $this->_log = ParserLog::create($this->_id, $action);
            }

            if ($action == 'parse') {
                $this->_hideNotParsed = true;
            }

            Yii::log(
                '----------------------Старт парсинга : ' . Yii::t('messages', 'parser_name.' . $this->_id) . '------------------------',
                CLogger::LEVEL_INFO,
                'advert_parser'
            );

            $this->_saveAdvertIdsList();

            if ($removedRows = $this->_removeFromSkip()) {
                Yii::log('Удаленно из пропущенных ' . $removedRows . ' записей', CLogger::LEVEL_INFO, 'advert_parser');
            }

            return true;
        } else {
            return false;
        }
    }

    public function afterAction($action, $params, $exitCode = 0)
    {
        $this->_hideNotParsed();

        Yii::log('----------------------Парсинг завершен : ' . Yii::t('messages',
                'parser_name.' . $this->_id) . '------------------------', CLogger::LEVEL_INFO,
            'advert_parser');

        if (in_array($action, static::$_actionsForLogging)) {
            $this->_log->countForProcess = $this->_advertProcessCounter;
            $this->_log->close();
        }

        parent::afterAction($action, $params);
    }

    public function actionHelp()
    {
        $this->_hideNotParsed = false;

        echo $this->_getHelp();
    }

    public function actionResetSource()
    {
        $sources = ParserSource::model()->findAllByAttributes(['parser_id' => $this->_id]);
        foreach ($sources as $source) {
            $source->hash = '';
            $source->status = 0;
            $source->save();
        }

        Yii::log('Статус и хеш сброшен', CLogger::LEVEL_INFO, 'advert_parser');
    }

    public function actionIndex()
    {
        $this->_hideNotParsed = false;

        echo $this->_getHelp();
    }

    public function actionHide()
    {
        $parserSource = ParserSource::model()->findAllByAttributes(['parser_id' => $this->_id]);
        $parserAdverts = CHtml::listData($parserSource, 'object_id', 'object_id');

        if (!empty($parserAdverts)) {
            foreach ($parserAdverts as $advertId) {
                if (empty($advertId)) {
                    continue;
                }

                /** @var Advert $model */
                $model = Page::getRelatedModelById($advertId);

                $model->post_status = Advert::STATUS_CLOSED;

                $model->save();
            }

            ParserSource::model()->deleteAllByAttributes(['parser_id' => $this->_id]);

            $this->_log->setData('hidden', $parserAdverts);
        }
    }

    public function actionParse()
    {
        $this->parse();
    }

    protected function _getHelp()
    {
        $help = 'Usage: ' . $this->getCommandRunner()->getScriptName() . ' ' . $this->getName();
        $options = $this->getOptionHelp();
        if (empty($options)) {
            return $help . "\n";
        }
        if (count($options) === 1) {
            return $help . ' ' . $options[0] . "\n";
        }
        $help .= " <action>\nActions:\n";
        foreach ($options as $option) {
            $help .= '    ' . $option . "\n";
        }

        return $help;
    }

    protected function _process()
    {
        $this->_advertProcessCounter++;
        try {
            if ($this->_mapper === null) {
                throw new Exception("Mapper не существует");
            }

            $sourceId = $this->_mapper->getSource();

            $attributes = $this->_mapper->process();

            $model = $this->_getModel($sourceId);

            $model->setAttributes($attributes);
            $source = $this->_getSourceModel($sourceId, true);
            if ($model->validate()) {
                $hashCurrent = $model->getHash();

                if ($source->getObjectHash() !== $hashCurrent) {
                    Yii::log('Хеш отсутствует или не совпадает', CLogger::LEVEL_INFO, 'advert_parser');

                    $isNewRecord = $model->getIsNewRecord();
                    if ($model->save()) {
                        if ($isNewRecord) {
                            $this->_log->setData('created', $model->id);
                        } else {
                            $this->_log->setData('updated', $model->id);
                        }

                        if ($model->post_status == Advert::STATUS_OPENED) {
                            $this->_log->setData('published', $model->id);
                        } elseif ($model->post_status == Advert::STATUS_MODERATED) {
                            $this->_log->setData('moderated', $model->id);
                        }

                        Yii::log('Сохраняем хеш модели', CLogger::LEVEL_INFO, 'advert_parser');
                        $source->hash = $hashCurrent;
                        $source->object_id = $model->id;
                        $source->status = ParserAdvertCarBase::STATUS_PARSED;
                        $source->save();
                    }
                } else {
                    Yii::log('Хеши совпадают', CLogger::LEVEL_INFO, 'advert_parser');
                    $this->_log->setData('notChange', $model->id);
                }
            } else {
                Yii::log('Пропущено - ' . $model->getTextErrors(), CLogger::LEVEL_INFO, 'advert_parser');
            }
        }
        catch (SkipException $e) {
            Yii::log(
                'Пропускаем объявление т.к. ' . $e->getMessage(),
                CLogger::LEVEL_INFO,
                'advert_parser'
            );

            $sourceId = $this->_mapper->getSource();
            $status = $e->getCode();
            $this->_getSourceModel($sourceId, true)->setStatus($status);

            $this->_log->setData('skipped', $sourceId);

            return;
        }
        catch (Exception $e) {
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'advert_parser');
            Yii::log($e->getFile() . ':' . $e->getLine(), CLogger::LEVEL_ERROR, 'advert_parser');
            Yii::log($e->getTraceAsString(), CLogger::LEVEL_ERROR, 'advert_parser');

            $this->_log->setData('error', $this->_mapper->getSource());

            return;
        }

        $this->_mapper->removeTempDir();

        $this->_removeAdvertIdFromList($model->id);
    }

    protected function _getModel($sourceId)
    {
        $source = $this->_getSourceModel($sourceId);

        $id = !is_null($source) ? $source->object_id : null;

        /** @var AdvertCar $model */
        $model = ParserAdvertCar::model()->findByAttributes(['id' => $id]);

        if (is_null($model)) {
            Yii::log('Объявления нет в БД', CLogger::LEVEL_INFO, 'advert_parser');
            $model = new ParserAdvertCar;
            $model->setScenario('parsed_insert');
        } else {
            Yii::log('Объявление есть в БД', CLogger::LEVEL_INFO, 'advert_parser');

            if ($model->post_status == $model::STATUS_DELETED) {
                throw new SkipException('Объявление существует и имеет статус `Удалено`');
            }

            if (!in_array($model->post_status, [$model::STATUS_OPENED, $model::STATUS_MODERATED])) {
                $model->post_status = $model::STATUS_OPENED;
            }

            $model->setScenario('parsed_update');
        }

        return $model;
    }

    protected function _getSourceModel($id, $createIfNotExists = false)
    {
        $source = ParserSource::model()->findByAttributes(['parser_id' => $this->_id, 'source_id' => $id]);

        if ($createIfNotExists && is_null($source)) {
            $source = new ParserSource();
            $source->source_id = $id;
            $source->parser_id = $this->_id;
        }

        return $source;
    }

    private function _saveAdvertIdsList()
    {
        $this->_advertIdsForHide = ParserSource::getParserObjectIds($this->_id);
        $this->_advertIdsForHideCountInBegin = count($this->_advertIdsForHide);
    }

    protected function _removeAdvertIdFromList($id)
    {
        unset($this->_advertIdsForHide[$id]);
    }

    private function _hideNotParsed()
    {
        if ($this->_hideNotParsed && !empty($this->_advertIdsForHide) && count($this->_advertIdsForHide) != $this->_advertIdsForHideCountInBegin) {
            foreach(array_chunk($this->_advertIdsForHide, 500) as $advertIdsForHide) {
                // Скрываем объявления и выключаем им турбо
                ParserAdvertCar::model()->updateByPk($advertIdsForHide, [
                    'post_status' => Advert::STATUS_CLOSED,
                    'turbo' => 0,
                ]);

                // Меняем статус у источника со "спарсенный" на "пропущенный"
                $condition = new CDbCriteria();
                $condition->addInCondition('object_id', $advertIdsForHide);
                ParserSource::model()->updateAll(['status' => ParserAdvertCarBase::STATUS_SKIP], $condition);

                $this->_log->setData('hidden', $advertIdsForHide);

                // Удаляем бесплатное турбо для скрытых
                $turboFree = Bill::getTarifByName(Bill::TARIF_TURBO_FREE);

                $result = Yii::app()->payment->searchBills(['tarif_id' => $turboFree->id, 'active' => 1]);

                $result = CHtml::listData($result, 'id', 'id');

                $condition = new CDbCriteria();
                $condition->addInCondition('advert_id', $advertIdsForHide);
                $condition->addInCondition('bill_id', array_values($result));
                AdvertBills::model()->deleteAllByAttributes([], $condition);

                Yii::log('Скрываем не спарсенные объявления', CLogger::LEVEL_INFO, 'advert_parser');

                // Remove sources what not have object_id
                $condition = new CDbCriteria();
                $condition->compare('object_id', 0);
                ParserSource::model()->deleteAll($condition);
            }

        }
    }

    private function _removeFromSkip()
    {
        $fourDaysAgo = strtotime('-1 hour');

        $condition = new CDbCriteria;
        $condition->compare('parser_id', $this->_id);
        $condition->compare('status', ParserAdvertCarBase::STATUS_SKIP);
        $condition->addCondition('`updated_at` < ' . $fourDaysAgo);

        return ParserSource::model()->deleteAll($condition);
    }

    abstract public function parse();
}