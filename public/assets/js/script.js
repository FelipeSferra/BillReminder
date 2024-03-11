function hexToRgb(hex) {
    hex = hex.replace(/^#/, '');

    var r = parseInt(hex.substring(0, 2), 16);
    var g = parseInt(hex.substring(2, 4), 16);
    var b = parseInt(hex.substring(4, 6), 16);

    return [r, g, b];
}

function applyCss(div, id, idColors,hexColor = '') {
    if (id !== 0 && hexColor === '') {
        hexColor = idColors[id];
    }

    if (hexColor && hexColor !== "#FFFFFF") {
        div.css({
            'background-color': 'rgba(' + hexToRgb(hexColor).join(
                ',') + ', 0.15)',
            'border-color': hexColor,
            'color': hexColor,
            'font-size': 'small',
            'font-weight': 'bold',
            'border-style': 'solid',
            'border-width': '1px',
            'text-align': 'center'
        })
    } else {
        div.css({
            'border-color': '#000000',
            'color': '#000000',
            'font-size': 'small',
            'font-weight': 'bold',
            'border-style': 'solid',
            'border-width': '1px',
            'text-align': 'center'
        })
    }
}

function formatValue(valor) {
    valor = parseFloat(valor);

    let valorFormatado = valor.toFixed(2);

    return valorFormatado;
}
