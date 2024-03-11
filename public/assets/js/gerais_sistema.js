/**
 * @preserve Funções gerais do sistema
 *  Autor: Victor Figueiredo
 *  Data Criação: 2022-12-09
 **/

'use strict';

// Desabilita a tradução do navegador
$(document).ready(function() {
    $('html').attr('translate', 'no');
});

// Tela de Loading
function TelaLoading(status) {

    if (status == true) {
        $("#loadingAguarde").addClass("activeAguarde");
    } else {
        $("#loadingAguarde").removeClass("activeAguarde");
    }//fim do if

}//fim do TelaLoading

// Retornar a data EN
function dataAtualEN(){
    let data = new Date();
    let diaHoje = ("0" + data.getDate()).slice(-2);
    let mesHoje = ("0" + (data.getMonth() + 1)).slice(-2);
    let anoHoje = data.getFullYear();

    return anoHoje + '-' + mesHoje + '-' + diaHoje;
}

// Retorna a data atual no formato BR
function dataAtualBR() {
    var tdate = new Date();
    var dd = tdate.getDate(); //yields day
    var MM = tdate.getMonth(); //yields month
    var yyyy = tdate.getFullYear(); //yields year
    var currentDate= dd + "/" +( MM+1) + "/" + yyyy;

    return currentDate;
}

// Semana atual completa
function semana_atual_completa(){
    // Semana atual
    var currentDate = new Date();
    var year = new Date(currentDate.getFullYear(), 0, 1);
    var days = Math.floor((currentDate - year) / (24 * 60 * 60 * 1000));
    var week = Math.trunc(( currentDate.getDay() + 1 + days) / 7);
    var fullYear = currentDate.getFullYear();
    var year_week = fullYear + '_' + week; // 2021_02

    return year_week;
}

// Semana atual completa
function semana_atual(){
    // Semana atual
    var currentDate = new Date();
    var year = new Date(currentDate.getFullYear(), 0, 1);
    var days = Math.floor((currentDate - year) / (24 * 60 * 60 * 1000));
    var week = Math.trunc(( currentDate.getDay() + 1 + days) / 7);
    // var fullYear = currentDate.getFullYear();
    // var year_week = fullYear + '_' + week; // 2021_02

    return week;
}


// Script para o Tooltip
$(function () {
    $("body").tooltip({
        selector: '[data-toggle="tooltip"]',
        container: 'body'
    });
});

// Select change
// $(document).on("change", "select", function () {
//     $("option[value=" + this.value + "]", this)
//         .attr("selected", true).siblings()
//         .removeAttr("selected")
// });

// Função de response do bootstrap table
function responseHandler(res) {
    $.each(res.rows, function (i, row) {
        row.state = $.inArray(row.id, selections) !== -1
    })
    return res
}

// Função de detail
function detailFormatter(index, row) {
    var html = []
    $.each(row, function (key, value) {
        html.push('<p><b>' + key + ':</b> ' + value + '</p>')
    })
    return html.join('')
}

// String prototype
String.prototype.contains = function (it) {
    return this.indexOf(it) != -1;
};

// Truncate string function
function truncate_str(str, length) {
    if (str.length > length) {
        return str.slice(0, length) + '...';
    } else return str;
}

// Função para inserir zero a esquerda
function insereZeroEsquerda(numero, quantidadeDigitos) {
    let numeroString = numero.toString();
    while (numeroString.length < quantidadeDigitos) {
        numeroString = '0' + numeroString;
    }
    return numeroString;
}


// Função para validar os valores
function converterParaFloat(valorFormatado) {
    // Verifica se a entrada é uma string vazia e retorna 0
    if (valorFormatado === '') {
        return 0;
    }

    // Verifica se é um número e retorna imediatamente
    if (typeof valorFormatado === 'number') {
        return valorFormatado;
    }

    // Verifica se a entrada é uma string
    if (typeof valorFormatado !== 'string') {
        throw new Error('A entrada deve ser uma string ou um número.');
    }

    // Verifica se a string está no formato correto de número flutuante (ex: "123.45")
    if (/^\d+(\.\d+)?$/.test(valorFormatado)) {
        return parseFloat(valorFormatado);
    }

    // Substitui pontos por nada e vírgulas por pontos
    var valorSemFormatacao = valorFormatado.replace(/\./g, '').replace(',', '.');

    // Verifica se o formato final é válido
    if (!/^[\d.]+$/.test(valorSemFormatacao)) {
        throw new Error('Formato de número inválido.');
    }

    return parseFloat(valorSemFormatacao);
}

// Bloquear teclas e inspecionar elemento no sistema para nao visualizar conteudo sensiveis do sistema
window.onload = function() {
    var url_sistema = window.location.href;

    // Valida se o sistema é o da produção
    if (url_sistema.includes("bill.sferra.tech") == false) {

        // Bloqueio do ctrl + u
        document.addEventListener("keydown", function (event) {
            if (event.ctrlKey && event.key === "u") {
                event.preventDefault();
            }

            // Bloqueia o F12
            if (event.key === "F12") {
                event.preventDefault();
            }

            // Bloqueia o ctrl + shift + i
            if (event.ctrlKey && event.shiftKey && event.key === "I") {
                event.preventDefault();
            }
        });

        //Bloqueia o clique direito
        document.addEventListener("contextmenu", function (event) {
            event.preventDefault();
        });
    }
}
