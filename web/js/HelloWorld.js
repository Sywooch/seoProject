class Hello extends React.Component {
    render() {
        return React.createElement('div', {className:'den'}, `Hello ${this.props.toWhat}`);
    }
}

ReactDOM.render(
    React.createElement(Hello, {toWhat: 'World'}, null),
    document.getElementById('root')
);

class Square extends React.Component {

    render() {
        var self = this;
        return React.createElement('button', {className:'square', onClick:

            function() {
                self.props.onClick();
            }
        }, this.props.value);
    }
}

class Board extends React.Component {
    constructor() {
        super();
        this.state = {
            squares: Array(9).fill(null),
            xIsNext: true,
        };
    }

    handleClick(i) {
        const squares = this.state.squares.slice();
        squares[i] = this.state.xIsNext ? 'X' : 'O';
        this.setState({
            squares: squares,
            xIsNext: !this.state.xIsNext,
        });
    }

    renderSquare(i) {
        var self = this;
        return React.createElement(Square, {value:this.state.squares[i], onClick:function() {self.handleClick(i)}});
    }

    render() {
        const status = 'Next player: X';
        return React.createElement('div', null,
            React.createElement('div', {className:'status'}, status),
            React.createElement('div', {className:'board-row'},
                this.renderSquare(0), this.renderSquare(1), this.renderSquare(2)
            ),
            React.createElement('div', {className:'board-row'},
                this.renderSquare(3), this.renderSquare(4), this.renderSquare(5)
            ),
            React.createElement('div', {className:'board-row'},
                this.renderSquare(6), this.renderSquare(7), this.renderSquare(8)
            )
        );
    }
}

class Game extends React.Component {
    render() {
        return React.createElement('div', {className:'game'},
            React.createElement('div', {className:'game-board'}, React.createElement(Board)),
                React.createElement('div', {className:'game-info'},
                    React.createElement('div'),
                    React.createElement('ol')
            )
        );
    }
}

// ========================================

ReactDOM.render(
    React.createElement(Game),
    document.getElementById('container')
);

function calculateWinner(squares) {
    const lines = [
        [0, 1, 2],
        [3, 4, 5],
        [6, 7, 8],
        [0, 3, 6],
        [1, 4, 7],
        [2, 5, 8],
        [0, 4, 8],
        [2, 4, 6],
    ];
    for (let i = 0; i < lines.length; i++) {
        const [a, b, c] = lines[i];
        if (squares[a] && squares[a] === squares[b] && squares[a] === squares[c]) {
            return squares[a];
        }
    }
    return null;
}

class Framework extends React.Component {
    constructor() {
        super();
        this.state = {
            count: 0,
        };
    }

    handleClick() {
        console.log('test');
        this.setState({count:this.state.count + 1});
    }

    render() {
        let self = this;
        return (
            <div className="container">
                <div className="counter">{this.state.count}</div>
                <img src={this.props.imageUrl} onClick={() => this.handleClick()}/>
                <h1>{this.props.title}</h1>
                <p>{this.props.subtitle}</p>
            </div>
        );
    }
}

var data =
    [
        {
            imageUrl:"https://facebook.github.io/react/img/logo.svg",
            title:"React",
            subtitle:"Библиотека для создания пользовательских интерфейсов"
        },
        {
            imageUrl:"https://angular.io/resources/images/logos/angular2/angular.svg",
            title:"Angular 2",
            subtitle:"Other Framework"
        }
    ];

ReactDOM.render(
    <div>
        {data.map(function(frame) {
            return (<Framework
                imageUrl={frame.imageUrl}
                title={frame.title}
                subtitle={frame.subtitle}
                key={frame.title}
            />);
        })}
    </div>
    , document.getElementById('jsx'));








