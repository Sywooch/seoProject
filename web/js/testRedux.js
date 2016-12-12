/*
 * типы действий
 */
const TOGGLE_TODO = 'TOGGLE_TODO';
const ADD_TODO = 'ADD_TODO';
const DELETE_TODO = 'DELETE_TODO';
const SET_VISIBILITY_FILTER = 'SET_VISIBILITY_FILTER';

/*
 * другие константы
 */

const VisibilityFilters = {
    SHOW_ALL: 'SHOW_ALL',
    SHOW_COMPLETED: 'SHOW_COMPLETED',
    SHOW_ACTIVE: 'SHOW_ACTIVE'
};

/*
 * генераторы действий
 */

function addTodo(text) {
    return { type: ADD_TODO, text }
}

function deleteTodo(id) {
    return { type: DELETE_TODO, id }
}

function toggleTodo(index) {
    return { type: TOGGLE_TODO, index }
}

function setVisibilityFilter(filter) {
    return { type: SET_VISIBILITY_FILTER, filter }
}

const initialState = {
    visibilityFilter: VisibilityFilters.SHOW_ALL,
    todos: []
};

function todos(state = [], action) {
    switch (action.type) {
        case ADD_TODO:
            return [
                ...state,
                {
                    text: action.text,
                    completed: false,
                    id:new Date().getTime()
                }
            ];
        case TOGGLE_TODO:
            return state.map((todo, index) => {
                if (index === action.index) {
                    return Object.assign({}, todo, {
                        completed: !todo.completed
                    })
                }
                return todo
            });
        case DELETE_TODO:
            return state.filter(function(todo) {
                return todo.id !== action.id;
            });
        default:
            return state
    }
}

function visibilityFilter(state = VisibilityFilters.SHOW_ALL, action) {
    switch (action.type) {
        case SET_VISIBILITY_FILTER:
            return action.filter;
        default:
            return state;
    }
}

const todoApp = Redux.combineReducers({
    visibilityFilter,
    todos
});

let store = Redux.createStore(todoApp);


// Выведем в консоль начальное состояние
console.log(store.getState());

// Каждый раз при обновлении состояния - выводим его
// Отметим, что subscribe() возвращает функцию для отмены регистрации слушателя
let unsubscribe = store.subscribe(() =>
    console.log(store.getState())
);

// Отправим несколько действий
store.dispatch(addTodo('Learn about actions'));
store.dispatch(addTodo('Learn about reducers'));
store.dispatch(addTodo('Learn about store'));
store.dispatch(toggleTodo(0));
store.dispatch(toggleTodo(1));
store.dispatch(setVisibilityFilter(VisibilityFilters.SHOW_COMPLETED));

// Прекратим слушать обновление состояния
unsubscribe();

const Todo = React.createClass(
    {
        render: function () {
            console.log(this.props);
            return (
                <li onClick={this.props.onClick}  >
                    <span style={{
                        textDecoration: this.props.completed ? 'line-through' : 'none'
                        }}>
                        {this.props.text}
                    </span>
                    <small onClick={this.props.onDeleteClick}>X</small>
                </li>
            );
        }
    }
);

const Link = React.createClass(
    {
        render: function () {
            if (this.props.active) {

                return React.createElement('span',null, this.props.children);
            }
            return (
                <a href="#" onClick={function (e) {e.preventDefault(); this.props.onClick()}.bind(this)}> {this.props.children}</a>
            );
        }
    }
);



const TodoList =
    React.createClass(
        {
            render: function () {
                let self = this;
                return (
                    <ul>
                        {this.props.todos.map(function(todo, index) {
                            return React.createElement(Todo, {
                                text:todo.text,
                                id:todo.id,
                                completed:todo.completed,
                                key:index,
                                onClick:function () {
                                    self.props.onTodoClick(index);
                                },
                                onDeleteClick: function() {
                                    self.props.onDeleteClick(todo.id);
                                }
                            });
                        })}
                    </ul>
                );
            }
        }
    );

const Footer = React.createClass(
    {
        render: function () {
            return (
                <p>
                    Show:
                    {" "}
                    <FilterLink filter="SHOW_ALL">
                        All
                    </FilterLink>
                    {", "}
                    <FilterLink filter="SHOW_ACTIVE">
                        Active
                    </FilterLink>
                    {", "}
                    <FilterLink filter="SHOW_COMPLETED">
                        Completed
                    </FilterLink>
                </p>
            );
        }
    }
);

const AddTodo = React.createClass(
    {
        render: function () {
            let self = this;
            console.log(this.props);
            return (
                <div>
                    <input ref={function (node) {self.input = node;}}/>
                    <button className="btn btn-primary" onClick={function () {self.props.onClick(self.input);}}>Add</button>
                </div>
            )
        }
    }
);

const App = React.createClass(
    {
        render: function () {
            return (
                <div>
                    <AddForm />
                    <VisibleTodoList />
                    <Footer />
                </div>
            );
        }
    }
);


TodoList.propTypes = {
    todos: React.PropTypes.arrayOf(React.PropTypes.shape({
        completed: React.PropTypes.bool.isRequired,
        text: React.PropTypes.string.isRequired
    }).isRequired).isRequired,
    onTodoClick: React.PropTypes.func.isRequired,
    onDeleteClick: React.PropTypes.func.isRequired,
};


Todo.propTypes = {
    onClick: React.PropTypes.func.isRequired,
    onDeleteClick: React.PropTypes.func.isRequired,
    completed: React.PropTypes.bool.isRequired,
    text: React.PropTypes.string.isRequired
};


Link.propTypes = {
    active: React.PropTypes.bool.isRequired,
    children: React.PropTypes.node.isRequired,
    onClick: React.PropTypes.func.isRequired
};

const getVisibleTodos = function(todos, filter) {
    switch (filter) {
        case 'SHOW_ALL':
            return todos;
        case 'SHOW_COMPLETED':
            return todos.filter(t => t.completed);
        case 'SHOW_ACTIVE':
            return todos.filter(t => !t.completed);
    }
};

const visibleMapStateToProps = function(state) {
    return {
        todos: getVisibleTodos(state.todos, state.visibilityFilter)
    }
};

const visibleMapDispatchToProps = function(dispatch) {
    return {
        onTodoClick: (id) => {
            dispatch(toggleTodo(id))
        },
        onDeleteClick: function (id) {
            dispatch(deleteTodo(id));
        }
    }
};
//
const VisibleTodoList = ReactRedux.connect(
    visibleMapStateToProps,
    visibleMapDispatchToProps
)(TodoList);

const filterMapStateToProps = function(state, ownProps) {
    return {
        active: ownProps.filter === state.visibilityFilter
    }
};

const filterMapDispatchToProps = function(dispatch, ownProps) {
    return {
        onClick: () => {
            dispatch(setVisibilityFilter(ownProps.filter))
        }
    }
};


const addMapDispatchToProps = function(dispatch) {
    return {
        onClick: (input) => {
            dispatch(addTodo(input.value))
            input.value = ''
        }
    }
};

const AddForm = ReactRedux.connect(
    null,
    addMapDispatchToProps,
)(AddTodo);


const CounterSmall = React.createClass(
    {
        render: function () {
            return (
                <small>{this.props.todos.length}</small>
            );

        }
    }
);

const Counter = ReactRedux.connect(
    function(state) {
        return {todos:state.todos};
    }
)(CounterSmall);

const FilterLink = ReactRedux.connect(
    filterMapStateToProps,
    filterMapDispatchToProps
)(Link);

var Provider = ReactRedux.Provider;

ReactDOM.render(
    <Provider store={store}>
        <App />
    </Provider>,
    document.getElementById('root')
);

ReactDOM.render(
    <Provider store={store}>
        <Counter />
    </Provider>,
    document.getElementById('count')
);

//notification
function getNotificationPermission() {
// Проверим, поддерживает ли браузер HTML5 Notifications
    if (!("Notification" in window)) {
        alert('Ваш браузер не поддерживает HTML Notifications, его необходимо обновить.');
    }

// Проверим, есть ли права на отправку уведомлений
    else if (Notification.permission === "granted") {
        return true;
    }

    else if (Notification.permission !== 'denied') {
        // Если прав нет, пытаемся их получить
        Notification.requestPermission(function (permission) {
// Если права успешно получены, отправляем уведомление
            if (permission === "granted") {
                return true;

            } else {
                alert('Вы запретили показывать уведомления');
            }
        });
    } else {
        alert('Вы запретили показывать уведомления');
    }
    return false;
}

function sentNotification(title, options) {
    if (getNotificationPermission()) {
        var notification = new Notification(title, options);
    }
}