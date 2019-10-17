$(function () {
    inicio();
});
function inicio() {
    ajustarValidacaoCampos();
    $('.accordion').accordion()
    $('.menu-ui').menu();
    $('.abas-ui').tabs({
        load: function () {
            $('.abas-ui').tabs();
        },
        select: function (event, ui) {
            $(".tabs li a .loader").remove();
            $(".tabs li a").eq(ui.index).append("<span class='loader'><img src='images/ajax-loader.gif'/></span>");
        }
    }).show('slow');
    //$('table#tabela_principal').css('opacity', '1');
    //fixarThead();
    //autocomplete();
    //chatBlur();
}

function fixarThead() {
    var tamanhos = [];

    $('table.topo_fixo').children("thead").children('tr').last().children('th').each(function (i) {
        tamanhos[i] = $(this).width() - 1;
        $(this).css('width', tamanhos[i] + "px");
    });
    var $table2 = $('table.topo_fixo').clone();
    var tamanho_atual = $('table.topo_fixo').width();
    //$('table.topo_fixo').css('width', tamanho_atual+"px");
    var $table3 = $('table.topo_fixo').clone();

    var t = $('table.topo_fixo').children('tbody').outerHeight();
    if (t > 200) {
        t = "200";
    } else {
        t += 3;
    }

    $('table.topo_fixo').children("tbody,tfoot").remove();

    $table2.removeClass('topo_fixo').children("caption, thead, tfoot").remove();
    $table2.children("tbody").children("tr").each(function () {
        $(this).children("td").each(function (i) {
            $(this).css('width', tamanhos[i]);
        });
    });

    var $div = $("<div/>");

    $div.css("height", t + "px").css("overflow", "auto")
    $div.append($table2);
    $('table.topo_fixo').parent().append($div);

    $table3.removeClass('topo_fixo').children("caption, thead, tbody").remove();
    $table3.children("tfoot").children("tr").each(function () {
        $(this).children("td").each(function (i) {
            $(this).css('width', tamanhos[i]);
        });
    });

    $('table.topo_fixo').parent().append($table3);
}

function ajustarValidacaoCampos() {
    $("input[data-tipo='int']").keyup(function () {
        var valor_ajustado = somenteNumeros($(this).val());
        $(this).val(valor_ajustado);
    });
    $("input[data-tipo='float']").keyup(function () {
        var valor_ajustado = ajustaFloat($(this).val());
        $(this).val(valor_ajustado);
    });
    $("input[data-tipo='time']").keyup(function () {
        var valor_ajustado = ajustaTime($(this).val());
        $(this).val(valor_ajustado);
    });
    var $datas = $("input[data-tipo='data']");
    $datas.each(function () {
        if ($(this).val().indexOf('-') > -1) {
            var tmp = $(this).val().split('-');
            var data_tmp = tmp[2] + "/" + tmp[1] + "/" + tmp[0];
            $(this).val(data_tmp);
        }
        $(this).keyup(function () {
            var valor_ajustado = ajustaData($(this).val());
            $(this).val(valor_ajustado);
        });
        $(this).blur(function () {
            if ($(this).val().length > 0 && $(this).val().length < 10) {
                $(this).val('');
                alerta('Formato da data inválida, informe DD/MM/AAAA');
                $(this).focus();
            } else {
                var tmp = $(this).val().split('/');
                if (parseInt((tmp[2] + '' + tmp[1] + '' + tmp[0])) < 19080101) {
                    $(this).val('');
                    alerta('A data não pode ser anterior ao ano de 1908');
                    $(this).focus();
                }
            }
        });
    });
    $("input[data-tipo='turno']").keyup(function () {
        var valor_ajustado = ajustaTurno($(this).val());
        $(this).val(valor_ajustado);
    });
    $("input[data-tipo='monetario']").keyup(function () {
        var valor_ajustado = ajustaMonetario($(this).val());
        $(this).val(valor_ajustado);
    });
    $("input[data-tipo='cpf']").keyup(function () {
        var valor_ajustado = ajustaCPF($(this).val());
        $(this).val(valor_ajustado);
    });
    $("input[data-tipo='nome']").keyup(function () {
        var valor_ajustado = ajustaNome($(this).val());
        $(this).val(valor_ajustado);
    });
    $("[data-required]").each(function () {
        var $esse = $(this);
        var id = $esse.attr('data-required');
        var $outroCampo = $('#' + id);
        if ($outroCampo.val() == '') {
            $esse.removeAttr('required');
        } else {
            $esse.attr('required', 'required');
        }
        $outroCampo.change(function () {
            if ($outroCampo.val() == '') {
                $esse.removeAttr('required');
            } else {
                $esse.attr('required', 'required');
            }
        });
    });
    $('form').submit(function () {
        mascara();
    })
}

function ajustaNome(valor) {
    return valor.replace(/[^a-zA-Z ]/g, '').toUpperCase();
}

function somenteNumeros(valor) {
    return valor.replace(/\D/g, '');
}

function ajustaMonetario(valor) {
    var tmp = valor.replace(/\D/g, '');
    var tamanho = tmp.length;
    if (tamanho < 2) {
        return valor.replace(/\D/g, '');
    }
    return tmp.substr(0, tamanho - 2) + "." + tmp.substr(tamanho - 2);
}

function ajustaData(valor) {
    valor = somenteNumeros(valor);
    if (valor.length < 3) {
        return valor;
    } else if (valor.length < 5) {
        return valor.substr(0, 2) + "/" + valor.substr(2);
    } else {
        return valor.substr(0, 2) + "/" + valor.substr(2, 2) + "/" + valor.substr(4, 4);
    }
}
function ajustaFloat(valor) {
    valor = valor.replace(',', '.');
    var tmp = valor.split('.');
    if (tmp.length > 1) {
        tmp[0] = tmp[0].replace(/\D/g, '');
        tmp[1] = tmp[1].replace(/\D/g, '');
        return tmp[0] + "." + tmp[1];
    } else {
        tmp[0] = tmp[0].replace(/\D/g, '');
        return tmp[0];
    }
}
function ajustaTime(valor) {
    valor = valor.replace(/\D/g, '');
    if (valor.length > 2) {
        var horas = valor.substr(0, 2);
        var minutos = valor.substr(2, 2);
        if (horas > 23) {
            horas = 23;
        }
        if (minutos > 59) {
            minutos = 59;
        }
        return horas + ":" + minutos;
    } else {
        if (valor > 23) {
            return 23;
        }
        return valor;
    }
}
function formataFloat(valor, casas) {
    valor = valor + '';
    valor = valor.replace(',', '.');
    var tmp = valor.split('.');
    if (tmp.length > 1) {
        tmp[0] = tmp[0].replace(/\D/g, '');
        tmp[1] = tmp[1].replace(/\D/g, '').substr(0, casas);
        while (tmp[1].length < casas) {
            tmp[1] = tmp[1] + "0";
        }
        return tmp[0] + "." + tmp[1];
    } else {
        tmp[0] = tmp[0].replace(/\D/g, '');
        tmp[1] = "";
        for (var i = 0; i < casas; i++) {
            tmp[1] = tmp[1] + "0";
        }
        return tmp[0] + "." + tmp[1];
    }
}

function ajustaCPF(valor) {
    valor = valor.replace(/\D/g, '');
    if (valor.length < 4) {
        return valor;
    } else if (valor.length < 7) {
        return valor.substr(0, 3) + "." + valor.substr(3);
    } else if (valor.length < 10) {
        return valor.substr(0, 3) + "." + valor.substr(3, 3) + "." + valor.substr(6);
    } else {
        return valor.substr(0, 3) + "." + valor.substr(3, 3) + "." + valor.substr(6, 3) + "-" + valor.substr(9, 2);
    }
}

function ajustaTurno(valor) {
    var t = valor.substr(0, 1);
    if (t == 'm' || t == 'M') {
        return 'M';
    }
    if (t == 't' || t == 'T') {
        return 'T';
    }
    if (t == 'n' || t == 'N') {
        return 'N';
    }
    return '';
}

function abrirPagina(pagina, novaAba) {
    if (typeof novaAba == 'undefined' || novaAba === false) {
        mascara();
        setTimeout(function () {
            window.location.href = pagina;
        }, 100);
    } else {
        window.open(pagina);
    }
}

function chamarPaginaAjax(pagina, parametros, callback, retry) {
    var p = parametros;
    if (typeof parametros == 'undefined') {
        parametros = '';
    }
    if (parametros == '') {
        parametros = 'ajax=true';
    } else {
        parametros = parametros + "&ajax=true";
    }
    var ajax = new XMLHttpRequest();
    ajax.open("POST", pagina, true);
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4) {
            if (ajax.status == 200) {
                if (typeof callback == 'function') {
                    callback(ajax.responseText);
                }
                preparaAutocomplete();
            } else {
                if (typeof retry == 'undefined') {
                    retry = 0;
                }
                if (retry < 4) {
                    retry++;
                    chamarPaginaAjax(pagina, p, callback, retry);
                } else {
                    alert("Erro " + ajax.status + "\nVocê pode estar tendo problemas com a sua conexão e suas alterações podem não estar sendo salvas");
                }
            }
        }
    }
    ajax.send(parametros);
}

function alerta(texto, funcaoOk) {
    var div = "<div class='alerta_x'>" +
            texto +
            "</div>";
    $('body').append(div);
    $('.alerta_x').dialog(
            {
                position: {at: "top"},
                modal: true,
                title: "Aviso",
                resizable: true,
                closeOnEscape: false,
                open: function (event, ui) {
                    $(this).parent().children().children('.ui-dialog-titlebar-close').hide();
                },
                buttons: {
                    Ok: function () {
                        if (typeof funcaoOk == 'function') {
                            funcaoOk();
                        } else {
                            $('.alerta_x').remove();
                        }
                    }
                }
            });
}
function confirma(texto, funcaoSim) {
    var div = "<div class='alerta_x'>" +
            texto +
            "</div>";
    $('body').append(div);
    $('.alerta_x').dialog(
            {
                position: {at: "top"},
                modal: true,
                resizable: true,
                title: "Confirmação",
                closeOnEscape: false,
                open: function (event, ui) {
                    $(this).parent().children().children('.ui-dialog-titlebar-close').hide();
                },
                buttons: {
                    Nao: function () {
                        $('.alerta_x').remove();
                    },
                    Sim: function () {
                        if (typeof funcaoSim == 'function') {
                            funcaoSim();
                        }
                        $('.alerta_x').remove();
                    }
                }
            });
}

function alterarPresenca(img) {
    mascara();
    var aluno = $(img).attr('data-aluno');
    var data = $(img).attr('data-data');
    var parametros = "aluno=" + aluno + "&data=" + data;

    if ($(img).attr('src').indexOf('vazio.png') > -1) {
        $(img).attr('src', '../auxiliares/ico/check.png');
        parametros = parametros + "&presenca=1";
    } else if ($(img).attr('src').indexOf('check.png') > -1) {
        $(img).attr('src', '../auxiliares/ico/cancelar.png');
        parametros = parametros + "&presenca=0";
    } else if ($(img).attr('src').indexOf('cancelar.png') > -1) {
        $(img).attr('src', '../auxiliares/ico/check_red.png');
        parametros = parametros + "&presenca=2";
    } else {
        $(img).attr('src', '../auxiliares/ico/vazio.png');
        parametros = parametros + "&presenca=null";
    }
    chamarPaginaAjax('presencaCrud.php', parametros, function (situacao) {
        var situacao_anterior = $('td.situacao_' + aluno).html();
        $('td.situacao_' + aluno).html(situacao);
        if (situacao != situacao_anterior) {
            chamarPaginaAjax('relatoriosPendentes.php?mes=0&aluno=' + aluno, '', function (r) {

                $('td.acoes_' + aluno).find('.qtd_pendente').html(r.replace(/\D/g, '').substr(0, 3));
                $('td.faltas_' + aluno).html($('img.aluno_' + aluno).filter("[src$='cancelar.png']").length);
                removerMascara();
            });
        } else {
            $('td.faltas_' + aluno).html($('img.aluno_' + aluno).filter("[src$='cancelar.png']").length);
            removerMascara();
        }
    });

}

function atualizaSituacao(id_aluno) {
    var presenca = 0;
    var falta = 0;
    var todo = 0;
    $('img.aluno_' + id_aluno).each(function () {
        if ($(this).attr('src').indexOf('check') > -1) {
            presenca++;
        }
        if ($(this).attr('src').indexOf('cancelar') > -1) {
            falta++;
        }
        todo++;
    });
    var situacao;
    var percentual_falta = parseFloat(falta / todo);

    if (percentual_falta == 0) {
        situacao = 'SEM FALTA';
    } else if (percentual_falta < 0.125) {
        situacao = 'FALTOSO';
    } else if (percentual_falta < 0.25) {
        situacao = 'GRAVE ';
    } else if (percentual_falta < 0.375) {
        situacao = 'MUITO GRAVE';
    } else {
        situacao = 'GRAVÍSSIMO';
    }
    if (situacao != situacao_anterior || true) {


    }

    $('td.faltas_' + id_aluno).html(falta);
    removerMascara();
}

function hoverHorizontalTabela() {
    $('table.zebrar').children('tbody').children('tr').each(function () {
        $(this).children('td').each(function (i) {
            $(this).attr('data-coluna', i);
            $(this).mouseover(function () {
                $('table.zebrar').children('tbody').children('tr').children("td[data-coluna='" + i + "']").addClass('hover');
            })
        });
    });
}

function gerarPdf(novaAba, local) {
    if (typeof local == 'undefined') {
        local = "conteudo";
    }
    var $tmp = $('#' + local).clone();
    var $tdAcoes = $tmp.find("thead").children("tr").children("th").last();
    if ($tdAcoes.length > 0 && $tdAcoes.last().html() == 'Ações') {
        $tmp.find("thead").children("tr").children("th").last().remove();
        $tmp.find("tbody").children("tr").each(function () {
            $(this).children("td").last().remove();
        });
    }
    $tmp.find("[onclick]").removeAttr('onclick');
    $tmp.find("a[href]").removeAttr('href');
    var conteudo = $tmp.html();
    if (typeof novaAba == 'undefined' || novaAba == false) {
        var target = "_self";
        mascara();
    } else {
        var target = "_blank";
    }
    var form = "<form target='" + target + "' id='form_pdf' method='POST' action='../auxiliares/gerarPdf.php'><input type='hidden' name='html' value='" + conteudo + "'/></form>";
    $('body').append(form);
    $('#form_pdf').submit();
    setTimeout(function () {
        $('#form_pdf').remove();
    }, 1000);
}
function gerarPdfSemCabecalho(novaAba, local) {
    if (typeof local == 'undefined') {
        local = "conteudo";
    }
    var $tmp = $('#' + local).clone();
    var $tdAcoes = $tmp.find("thead").children("tr").children("th").last();
    if ($tdAcoes.length > 0 && $tdAcoes.last().html() == 'Ações') {
        $tmp.find("thead").children("tr").children("th").last().remove();
        $tmp.find("tbody").children("tr").each(function () {
            $(this).children("td").last().remove();
        });
    }
    $tmp.find("[onclick]").removeAttr('onclick');
    $tmp.find("a[href]").removeAttr('href');
    var conteudo = $tmp.html();
    if (typeof novaAba == 'undefined' || novaAba == false) {
        var target = "_self";
        mascara();
    } else {
        var target = "_blank";
    }
    var form = "<form target='" + target + "' id='form_pdf' method='POST' action='../auxiliares/gerarPdfSemCabecalho.php'><input type='hidden' name='html' value='" + conteudo + "'/></form>";
    $('body').append(form);
    $('#form_pdf').submit();
    setTimeout(function () {
        $('#form_pdf').remove();
    }, 1000);
}

function exibeRelatoriosPendentes(id_aluno) {
    mascara();
    chamarPaginaAjax('listagemRelatoriosPendentes.php?aluno=' + id_aluno, '', function (r) {
        alterarConteudoMascara(r);
    });
}
function exibeRelatoriosEntregues(id_aluno) {
    removerMascara();
    mascara();
    chamarPaginaAjax('listagemRelatoriosEntregues.php?aluno=' + id_aluno, '', function (r) {
        alterarConteudoMascara(r);
    });
}

function mascara() {
    removerMascara();
    var tela = "<span class='mascara' style='display: inline-block; height: 100%; width: 100%; position: fixed; top: 0; left: 0; background-color: #000000; opacity: 0.3; z-index: 1;'></span>";
    var conteudo = "<span class='mascara' style='display: inline-block; height: 100%; width: 100%; position: fixed; top: 0; left: 0; z-index: 2; vertical-align: middle; text-align: center;'>" +
            "<span style='display: inline-block; width: 550px; height: 20px; margin-top: 30px; text-align: right;'>" +
            "   <img class='remover_mascara' title='Fechar' src='../auxiliares/ico/cancelar.png' style='width: 16px; cursor: pointer; padding-right: 5px;' onclick='removerMascara()'/>" +
            "</span><br/>" +
            "<span id='conteudo_mascara' style='overflow: auto; display: inline-block; width: 550px; height: 550px; background-color: #FFFFFF; border-radius: 10px;'></span>" +
            "<table class='aguarde_mascara' style='margin: 0 auto; height: 75%;'><tr><td><img src='../auxiliares/img/aguarde.gif' style='border-radius: 10px;'/></td></tr></table>" +
            "</span>";
    $('body').append(tela);
    $('body').append(conteudo);
    $('.remover_mascara').hide();
    $('#conteudo_mascara').hide();
}

function removerMascara() {
    $('.mascara').remove();
}

function alterarConteudoMascara(conteudo) {
    $('#conteudo_mascara').html(conteudo);
    $('.aguarde_mascara').hide();
    $('#conteudo_mascara').show();
    $('.remover_mascara').show();
}

function gerarGraficoBarras(id_canvas, grafico) {
    $('.' + id_canvas).hide();
    var $canvas = $("#barra_" + id_canvas);
    $canvas.show();
    var contexto2d = $canvas[0].getContext("2d");
    var graficoBarras = new Chart(contexto2d).Bar(grafico, {
        responsive: true,
        barValueSpacing: 1,
        barDatasetSpacing: 0,
        tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",
        tooltipFontSize: 10,
        tooltipTitleFontSize: 12,
        multiTooltipTemplate: "<%= datasetLabel.replace(/.*<label>/i, '').replace(/<.label>.*/i, '') %>:<%= value %>"
    });
    $('#' + id_canvas + '_legenda').html(graficoBarras.generateLegend());
}
function gerarGraficoLinhas(id_canvas, grafico) {
    $('.' + id_canvas).hide();
    var $canvas = $("#linha_" + id_canvas);
    $canvas.show();
    var contexto2d = $canvas[0].getContext("2d");
    var graficoBarras = new Chart(contexto2d).Line(grafico, {
        responsive: true,
        tooltipFontSize: 10,
        tooltipTitleFontSize: 12,
        tooltipYPadding: 2,
        showTooltips: true,
        multiTooltipTemplate: "<%= datasetLabel.replace(/.*<label>/i, '').replace(/<.label>.*/i, '') %>:<%= value %>"
    });
    $('#' + id_canvas + '_legenda').html(graficoBarras.generateLegend());
}
function gerarGraficoRadar(id_canvas, grafico) {
    $('.' + id_canvas).hide();
    var $canvas = $("#radar_" + id_canvas);
    $canvas.show();
    var contexto2d = $canvas[0].getContext("2d");
    var graficoBarras = new Chart(contexto2d).Radar(grafico, {
        responsive: true,
        tooltipFontSize: 10,
        tooltipTitleFontSize: 12,
        multiTooltipTemplate: "<%= datasetLabel.replace(/.*<label>/i, '').replace(/<.label>.*/i, '') %>:<%= value %>"
    });
    $('#' + id_canvas + '_legenda').html(graficoBarras.generateLegend());
}
function gerarGraficoPizza(id_canvas, graficos) {
    $('.' + id_canvas).hide();
    $("#pizza_" + id_canvas).show();
    for (var i = 0; i < graficos.length; i++) {
        var $canvas = $("#pizza_" + id_canvas + "_" + i);
        $canvas.show();
        var contexto2d = $canvas[0].getContext("2d");
        var graficoBarras = new Chart(contexto2d).Pie(graficos[i], {
            responsive: true
        });
    }
}
function submeterFormAjax(id_destino, callback) {
    var $form = $('form').first();
    var parametros = $form.find("input, select, textarea").serialize();
    var pagina = $form.attr('action');
    var metodo = $form.attr('method');
    if (metodo == 'GET') {
        pagina = pagina + "?" + parametros;
        parametros = "";
    }
    mascara();
    chamarPaginaAjax(pagina, parametros, function (r) {
        $('#' + id_destino).html(r);
        inicio();
        removerMascara();
        if (typeof callback == "function") {
            callback(r);
        }
    });
}

function carregarPagina(pagina, id_local) {
    chamarPaginaAjax(pagina, '', function (r) {
        $('#' + id_local).html(r);
    });
}

function paginaMascara(pagina, parametros) {
    mascara();
    chamarPaginaAjax(pagina, parametros, function (r) {
        alterarConteudoMascara(r);
    });
}

function formataHora(valor) {
    valor = valor.replace(/\D/g, '');
    if (valor.length < 3) {
        return valor;
    } else {
        return valor.substr(0, 2) + ":" + valor.substr(2, 2);
    }
}

function ajustaAlunoForm() {
    var nacionalidade = $('#nacionalidade').val();
    if (nacionalidade == 1) {
        $('.brasileiro').show('slow');
        $('.estrangeiro').find("input[type='text'], select, textarea").val('');
        $('.estrangeiro').find("input[type='radio'], input[type='checkbox']").removeAttr('checked');
        $('.estrangeiro').hide();
    } else {
        $('.estrangeiro').show('slow');
        $('.brasileiro').find("input[type='text'], select, textarea").val('');
        $('.brasileiro').find("input[type='radio'], input[type='checkbox']").removeAttr('checked');
        $('.brasileiro').hide();
    }
    if ($('#modelo_novo_certidao').is(":checked")) {
        $('.modelo_novo').show('slow')
        $('.modelo_antigo').find("input[type='text'], select, textarea").val('');
        $('.modelo_antigo').find("input[type='radio'], input[type='checkbox']").removeAttr('checked');
        $('.modelo_antigo').hide();
    } else {
        $('.modelo_antigo').show('slow')
        $('.modelo_novo').find("input[type='text'], select, textarea").val('');
        $('.modelo_novo').find("input[type='radio'], input[type='checkbox']").removeAttr('checked');
        $('.modelo_novo').hide()
    }
}

function ajustaMatriculaForm() {
    if ($('#transporte_publico').is(':checked')) {
        $('.transporte_publico').show('slow');
    } else {
        $('.transporte_publico').find("input[type='checkbox'], input[type='radio']").removeAttr('checked');
        $('.transporte_publico').hide();
    }
}

function ajustaRelatorioForm() {
    if ($('#doenca').is(':checked')) {
        $('.doenca').show('slow');
        if (!$('#atestado').is(':checked')) {
            $('.atestado').show('slow');
        } else {
            $('.atestado').hide('slow');
            $('.atestado').find("input[type='checkbox'], input[type='radio']").removeAttr('checked');
        }
    } else {
        $('.doenca').hide('slow');
        $('.doenca').find("input[type='checkbox'], input[type='radio']").removeAttr('checked');
    }

    if ($('#outro_faltando').is(':checked')) {
        $('.outro_faltando').show();
        $('.outro_faltando').attr('required', 'required');
    } else {
        $('.outro_faltando').hide('slow');
        $('.outro_faltando').val('').removeAttr('checked');
    }
    if ($('#outro_atestado').is(':checked')) {
        $('.outro_atestado').show();
        $('.outro_atestado').attr('required', 'required');
    } else {
        $('.outro_atestado').hide('slow');
        $('.outro_atestado').val('').removeAttr('checked');
    }
}

function ajustaEventoForm() {
    if ($('#privado').is(':checked')) {
        $('.privado').show();
    } else {
        $('.privado').find("input[type='checkbox']").removeAttr('checked');
        $('.privado').hide();
    }
}

function ajustaAlunoBusca() {
    if ($('#frequencia').is(':checked')) {
        $('.frequencia').show('slow');
    } else {
        $('.frequencia').find('select, input').val('');
        $('.frequencia').hide();
    }
}
var anotacao = false;
function passarAnotacao(passar) {
    if (anotacao) {
        return false;
    }
    anotacao = true;
    $('.anotacao').not(".visivel").css('left', '400px').css('opacity', '0');
    if ($('.anotacao.visivel').length == 0) {
        $('.anotacao').first().animate({left: '50px', opacity: '1'}, 1000, function () {
            anotacao = false;
        }).addClass('visivel');
    } else {
        if (typeof passar == 'undefined') {
            passar = true;
        }
        var $esconder = $('.anotacao.visivel');
        if (passar) {
            var $mostrar = $('.anotacao.visivel').siblings('.anotacao').first();
            $esconder.animate({left: '-450px', opacity: '0'}, 1000, function () {
                $esconder.css('left', '400px');
                $esconder.parent().append($esconder);
                anotacao = false;
            }).removeClass('visivel');
        } else {
            var $mostrar = $('.anotacao.visivel').siblings('.anotacao').last();
            $mostrar.css('left', '-450px');
            $esconder.animate({left: '400px', opacity: '0'}, 1000, function () {
                //$esconder.css('left', '400px');
                $esconder.parent().prepend($esconder);
                anotacao = false;
            }).removeClass('visivel');
        }
        $mostrar.animate({left: '50px', opacity: '1'}, 1000).addClass('visivel');
    }
}

function novaAnotacao() {
    if (anotacao) {
        return false;
    }
    anotacao = true;
    var $esconder = $('.anotacao.visivel');
    $esconder.animate({top: '800px', opacity: '0'}, 1000, function () {

    }).removeClass('visivel').addClass('tmp');
    $('.add_anotacao').animate({top: '50px', opacity: '1'}, 1000);
}

function cancelaAnotacao() {
    var $esconder = $('.add_anotacao');
    $esconder.animate({top: '-400px', opacity: '0'}, 1000);
    $('.anotacao.tmp').animate({top: '50px', opacity: '1'}, 1000, function () {
        anotacao = false;
    }).removeClass('tmp').addClass('visivel');
}

function adicionarAnotacao() {
    var descricao = $('#descricao_anotacao').val();
    chamarPaginaAjax('anotacaoCrud.php', 'descricao=' + descricao, function (r) {
        anotacao = false;
        paginaMascara('anotacoes.php');
    });
}

function autocomplete() {
    $("input[data-tipo='auto']").each(function () {
        var $this = $(this);
        var name = $this.attr('name');
        $this.attr('name', name + "_label");
        $this.blur(function () {
            if ($this.siblings("[type='hidden'].autocomplete").length == 0 || $this.siblings("[type='hidde'].autocomplete").last().val() == '') {
                $(this).val('');
            }
        })
        $this.css('width', '70%');
        $this.parent().append("<img class='autocomplete' onclick=\"$(this).parent().children('input').val('').keydown();\" style='width: 20px; cursor: pointer;' src='../auxiliares/ico/lupa.png'/>");
        var pagina = $this.attr('data-pagina');
        if (pagina.indexOf('?') == -1) {
            pagina = pagina + '?ajax=true';
        } else {
            pagina = pagina + "&ajax=true";
        }
        $(this).autocomplete({
            source: pagina,
            minLength: 0,
            search: function (event, ui) {
                $this.siblings("img.autocomplete").attr("src", "../auxiliares/img/carregando.gif");
            },
            select: function (event, ui) {
                if (ui.item.id != '') {
                    criarCampoAutocomplete(ui.item.id, $this);
                }
            },
            response: function (event, ui) {
                $this.siblings("img.autocomplete").attr("src", "../auxiliares/ico/lupa.png");
            },
            open: function (event, ui) {

            }
        });
        $(this).data("ui-autocomplete")._renderItem = function (ul, item) {
            if (item.value == '') {
                return $('<li class="ui-state-disabled">' + item.label + '</li>').appendTo(ul);
            } else {
                return $("<li>")
                        .append(item.label)
                        .appendTo(ul);
            }
        }
        jQuery.ui.autocomplete.prototype._resizeMenu = function () {
            var ul = this.menu.element;
            ul.outerWidth(this.element.outerWidth());
            ul.outerHeight(300);
            ul.css('overflow', 'auto');
        }
        jQuery.ui.autocomplete.prototype._renderMenu = function (ul, items) {
            var that = this,
                    currentCategory = "";
            $.each(items, function (index, item) {
                var li;
                if (typeof item.category != 'undefined' && item.category != currentCategory) {
                    ul.append("<li class='ui-state-disabled'>" + item.category + "</li>");
                    currentCategory = item.category;
                }
                li = that._renderItemData(ul, item);
                if (item.category) {
                    li.attr("aria-label", item.category + " : " + item.label);
                }
            });
        }
    });
}

function criarCampoAutocomplete(id, $elemento) {
    var nome = $elemento.attr('name').substr(0, $elemento.attr('name').length - 6);
    $elemento.attr('readonly', 'readonly');
    $elemento.siblings("input.autocomplete[type='hidden'], img.autocomplete").remove();
    $elemento.parent().append("<img class='autocomplete' onclick='limparCampoAutocomplete(this)' style='width: 20px; margin-top: 5px; cursor: pointer;' src='../auxiliares/ico/menos.png'/><input class='autocomplete' type='hidden' name='" + nome + "' value='" + id + "'/>");
}

function limparCampoAutocomplete(img) {
    var $elemento = $(img).parent().children("input[data-tipo='auto']");
    $elemento.removeAttr('readonly');
    $elemento.val('');
    $elemento.siblings("input.autocomplete[type='hidden'], img.autocomplete").remove();
    $elemento.parent().append("<img class='autocomplete' onclick=\"$(this).parent().children('input').val('').keydown();\" style='width: 20px; cursor: pointer;' src='../auxiliares/ico/lupa.png'/>");
}

function carregaAlunoBusca(nome, escola, serie, frequencia, infrequencia, mes, numero) {
    if (numero > 9) {
        $('#aluno_busca').find('tr.tmp').remove();
        $('#aluno_busca').children('tbody').append("<tr class='tmp'><td colspan='100%' style='text-align: center; background: none; background-color: #FFFFFF; cursor: pointer;' onclick=\"carregaAlunoBusca('" + nome + "', '" + escola + "', '" + serie + "', '" + frequencia + "', '" + infrequencia + "', '" + mes + "', 0)\">-- Há mais resultados, clique aqui para exibi-los --</td></tr>");
    } else {
        $('#aluno_busca').find('tr.tmp').remove();
        $('#aluno_busca').children('tbody').append("<tr class='tmp'><td colspan='100%' style='text-align: center; background: none; background-color: #FFFFFF;'><img src='../auxiliares/img/barra_carregando.gif'/></td></tr>");
        var qtd = $('#aluno_busca').children('tbody').children('tr').not(".tmp").length;

        chamarPaginaAjax('alunoBuscaLinha.php?nome=' + nome + '&escola=' + escola + '&serie=' + serie + '&frequencia=' + frequencia + '&infrequencia=' + infrequencia + '&mes=' + mes + '&offset=' + qtd, '', function (r) {
            if (r != '') {
                $('#aluno_busca').find('tr.tmp').remove();
                $('#aluno_busca').children('tbody').append(r);
                $('#aluno_busca').children('tbody').append("<tr class='tmp'><td colspan='100%' style='text-align: center; background: none; background-color: #FFFFFF;'><img src='../auxiliares/img/barra_carregando.gif'/></td></tr>");
                setTimeout(function () {
                    numero++;
                    carregaAlunoBusca(nome, escola, serie, frequencia, infrequencia, mes, numero);
                }, 400);
            } else {
                $('#aluno_busca').find('tr.tmp').remove();
            }
        });
    }
}

function checkHomonimo(nome) {
    if (nome == '') {
        return false;
    }
    mascara();
    chamarPaginaAjax('alunoHomonimo.php?nome=' + nome, '', function (r) {
        if (r != '') {
            $('#nome').val('');
            alterarConteudoMascara(r);
        } else {
            removerMascara();
        }
    });
}

function carregarMapa() {
    if ($('#mapa').length == 0) {
        $('body').append("<div id='mapa'></div>");
    }
    initialize();
}

function validaNomeMae() {
    mascara();
    var aluno = $('#nome').val();
    if (aluno == '') {
        removerMascara();
        alerta("O nome do aluno é obrigatório");
        return false;
    }

    var mae = $('#mae').val();
    if (mae == '') {
        $('#form').submit();
    } else {
        var id = 0;
        if ($('#id_aluno').length > 0) {
            id = $('#id_aluno').val();
        }
        chamarPaginaAjax('alunoDuplicidade.php?id=' + id + '&nome=' + aluno + '&mae=' + mae, '', function (r) {
            if (r != 'OK') {
                removerMascara();
                alerta("Já existe um aluno cadastrado com o mesmo nome e mesma mãe!");
                return false;
            } else {
                $('#form').submit();
            }
        });
    }
}

var atualizaNaoLidas;
var atualizaMensagem;

function programarAtualizacaoChat() {
    clearTimeout(atualizaNaoLidas);
    clearTimeout(atualizaMensagem);
    atualizaNaoLidas = setTimeout(function () {
        atualizaChat();
    }, 2000);
}

function atualizaChat() {
    var horario = $('#horario_chat').val();
    chamarPaginaAjax('chatAtualiza.php?horario=' + horario, '', function (r) {
        if (r == 'S') {
            clearTimeout(atualizaMensagem);
            clearTimeout(atualizaNaoLidas);
            chamarPaginaAjax('chat.php', '', function (conteudoChat) {
                $('.conteudo_chat').html(conteudoChat);
                programarAtualizacaoChat();
            });
        } else {
            programarAtualizacaoChat();
        }
    })
}
function atualizarMensagem() {
    clearTimeout(atualizaNaoLidas);
    clearTimeout(atualizaMensagem);
    var id_escola = $('#escola_mensagem_destino').val();
    chamarPaginaAjax('chatLerMensagem.php?escola=' + id_escola, '', function (r) {
        var xml = $.parseXML("<span>" + r + "</span>");
        $('.listagem_mensagens').html($(xml).find('.listagem_mensagens').html());
        clearTimeout(atualizaNaoLidas);
        clearTimeout(atualizaMensagem);
        atualizaMensagem = setTimeout(function () {
            atualizarMensagem();
        }, 2000);
    });
}
function abrirChat() {
    if (parseInt($('span.chat').css('bottom').replace(/\D/g, '')) > 1) {
        fecharChat();
    } else {
        $('span.chat').first().animate({bottom: '200px', width: '400px'}, 'slow');
        $('span.itens_chat').first().animate({height: '200px', width: '400px'}, 'slow');
    }
}
function fecharChat() {
    clearTimeout(atualizaMensagem);
    clearTimeout(atualizaNaoLidas);
    $('span.chat').first().animate({bottom: '1px', width: '160px'}, 'slow');
    $('span.itens_chat').first().animate({height: '0px', width: '160px'}, 'slow');
    $('span.ler_mensagem_chat').first().animate({height: '0', width: '165px'}, 'slow');
    programarAtualizacaoChat();
}
function abrirMensagemChat(id_escola) {
    clearTimeout(atualizaNaoLidas);
    if (parseInt($('span.itens_chat').first().css('height').replace(/\D/g, '')) > 0) {
        $('span.itens_chat').first().css('height', '0px');
        $('span.ler_mensagem_chat').first().css('height', '200px').css('width', '400px');
        $('span.ler_mensagem_chat').first().html("<img src='../auxiliares/img/aguarde.gif'/>");
    }
    setTimeout(function () {
        chamarPaginaAjax('chatLerMensagem.php?escola=' + id_escola, '', function (r) {
            $('span.ler_mensagem_chat').first().html(r);
            $('span.chat').first().animate({width: '493px', bottom: '400px'});
            $('span.ler_mensagem_chat').first().animate({width: '500px', height: '400px'}, function () {
                var tamanho = $('span.ler_mensagem_chat').outerHeight() * 1.5;
                $('span.ler_mensagem_chat').animate({scrollTop: 9999 + "px"}, 0);
            });
            atualizarMensagem();
        });
    }, 400);
}
function enviarMensagem() {
    var texto = $('textarea.pergunta').val();
    var escola_origem = $('#escola_mensagem_origem').val();
    var escola = $('#escola_mensagem_destino').val();
    chamarPaginaAjax('mensagemEscolaCrud.php?escola=' + escola + '&mensagem=' + texto + '&escola_origem=' + escola_origem, '', function (r) {
        abrirMensagemChat(escola);
    });
}
function voltarChat() {
    clearTimeout(atualizaMensagem);
    clearTimeout(atualizaNaoLidas);
    $('span.ler_mensagem_chat').first().animate({height: '0', width: '165px'}, 'slow');
    $('span.chat').first().animate({bottom: '200px', width: '400px'}, 'slow');
    $('span.itens_chat').first().animate({height: '200px', width: '400px'}, 'slow');
    programarAtualizacaoChat();
}
function chatBlur() {
    $('body').click(function (event) {
        if ($(event.target).closest('.conteudo_chat').length == 0) {
            fecharChat();
        }
    })
}

function adicionarPonto() {
    if ($('.pontos_coleta').length > 0) {
        var qtd = $('.pontos_coleta').last().attr('data-numero');
        qtd++;
    } else {
        var qtd = 0;
    }

    chamarPaginaAjax('pontoFormOpcao.php?qtd=' + qtd, '', function (r) {
        $('#pontos').append(r);
        $('#pontos').append($('#destino'));
    });
}

function definirRota() {
    var x = $("[name='endereco_origem_x']").val();
    var y = $("[name='endereco_origem_y']").val();
    var origem = "(" + x + "," + y + ")";

    x = $("[name='endereco_destino_x']").val();
    y = $("[name='endereco_destino_y']").val();
    var destino = "(" + x + "," + y + ")";

    var pontos = [];
    var i = 0;
    $('.pontos_coleta').each(function () {
        x = $(this).find("[name^='endereco_'][name$='_x']").val();
        y = $(this).find("[name^='endereco_'][name$='_y']").val();
        pontos[i] = {location: "(" + x + "," + y + ")", stopover: true};
        i++;
    });

    criarRota(origem, destino, pontos);
}

function removerEvento(id_evento) {
    confirma("Tem certeza que deseja remover o evento selecionado?", function () {
        chamarPaginaAjax('eventoCrud.php?acao=deletar&id=' + id_evento, '', function (r) {
            abrirPagina('calendario.php');
        });
    });
}

function removerFeriado(data) {
    confirma("Tem certeza que deseja remover o feriado do dia selecionado?", function () {
        chamarPaginaAjax('feriadoCrud.php?acao=deletar&data=' + data, '', function (r) {
            abrirPagina('calendario.php');
        });
    });
}

function alterarSerieMatricula(id_escola, id_serie, id_aluno) {
    mascara();
    chamarPaginaAjax('turmaSelect.php?disponivel=true&escola=' + id_escola + '&serie=' + id_serie, '', function (r) {
        if (r.indexOf('CADASTRAR-NOVA-TURMA') == -1) {
            $('#turma').html(r);
        } else {
            $('#turma').html('<option value=\'\'>Sem vagas para esta série</option>');
            alerta('Não há vagas disponíveis para esta série, o sistema criará uma nova turma para disponibilizar mais vagas', function () {
                abrirPagina('turmaNova.php?aluno=' + id_aluno + '&serie=' + $('#serie').val())
            });
        }
    });
    chamarPaginaAjax('subserieSelect.php?serie_pai=' + id_serie, '', function (s) {
        if (s == '') {
            $('#subserie').html("<option value=''>Serie Unica</option>");
            $('.subserie').removeAttr('required').hide('slow');
        } else {
            $('#subserie').attr('required', 'required').html(s);
            $('.subserie').show('slow');
        }
        removerMascara();
    });
}

function expandirMenu() {
    $('.menu').css('overflow-y', 'scroll').animate({height: '300px'}, 'slow', function () {
        $('.expandir_menu').hide();
        $('.esconder_menu').show();
    });
}
function esconderMenu() {
    $('.menu').css('overflow-y', 'hidden').animate({height: '1px'}, 'slow', function () {
        $('.expandir_menu').show();
        $('.esconder_menu').hide();
    });
}

function checkValorMaximo(campo_origem, valor_maximo) {
    var $campo = $(campo_origem);
    if (valor_maximo == '' || typeof valor_maximo == 'undefined') {
        $(campo_origem).val('');
        alerta('Nao foi possivel localizar o valor maximo do campo');
        return false;
    } else if (valor_maximo < parseFloat($(campo_origem).val())) {
        $(campo_origem).val('');
        alerta('O valor maximo permitido eh de ' + valor_maximo, function () {
            $('.alerta_x').remove();
            $campo.focus();
        });
        return false;
    } else {
        return true;
    }
}

function getOptionSelected(select) {
    return $(select).find("option[value='" + $(select).val() + "']").last()[0];
}

function getByName(nome) {
    return $("[name='" + nome + "']").last()[0];
}

function ajustarValorServico() {
    if ($('#porcentual').val() == '') {
        $('#porcentual').val('0.00');
        $('#valor').val('0.00');
    } else {
        var porcentual = parseFloat($('#porcentual').val());
        var total = parseFloat($('#total').html());
        var x = formataFloat((total * (porcentual / 100.0)), 2)
        $('#valor').val(x);
    }
}
function ajustarPercentualServico() {
    if ($('#valor').val() == '') {
        $('#porcentual').val('0.00');
        $('#valor').val('0.00');
    } else {
        var valor = parseFloat($('#valor').val());
        var total = parseFloat($('#total').html());
        var x = formataFloat((valor * 100.0) / total, 2);
        $('#porcentual').val(x);
    }
}

function ajustarPedidos() {
    var tamanho_caixa = $('div.pedidos').first().outerWidth();

    var tamanho_item = 300;
    var qtd_itens = $('div.pedidos').find('span.pedido').length;
    if (qtd_itens > 1) {
        var inicio = -280;
        if (qtd_itens > 3 && tamanho_caixa > 900) {
            $('div.pedidos').find('span.pedido').removeClass('selecionado').first().next().next().addClass('selecionado');
        } else {
            $('div.pedidos').find('span.pedido').removeClass('selecionado').first().next().addClass('selecionado');
        }

    } else {
        var inicio = 20;
        $('div.pedidos').find('span.pedido').removeClass('selecionado').first().addClass('selecionado');
    }
    $('div.pedidos').find('span.pedido').css('width', tamanho_item + 'px');

    $('div.pedidos').find('span.pedido').each(function () {
        $(this).animate({left: inicio + 'px'}, 'fast');
        inicio += tamanho_item;
    });
    if ($('div.pedidos').find('span.pedido.selecionado').first().hasClass('preparando')) {
        $('#bt_cancelar').addClass('btn-danger').removeClass('btn-primary').html('Aguardando [D]');
    } else {
        $('#bt_cancelar').removeClass('btn-danger').addClass('btn-primary').html('Preparando [D]');
    }

    var qtd_aguardando = $('div.pedidos').find('span.pedido').not('.preparando').length;
    var qtd_preparando = $('div.pedidos').find('span.pedido.preparando').length;
    $('#qtd_aguardando').html(qtd_aguardando);
    $('#qtd_preparando').html(qtd_preparando);

    var id_maior = 0;
    $('div.pedidos span.pedido input.ids').each(function () {
        if (id_maior < parseInt($(this).val(), 10)) {
            id_maior = parseInt($(this).val(), 10);
            $('div.pedidos span.pedido').removeClass('maior_id');
            $(this).closest('span.pedido').addClass('maior_id');
        }
    });
    $('#id_maior').val(id_maior);

}
function avancarPedido() {
    $('div.pedidos').find('span.ultimo').before($('div.pedidos').find('span.pedido').first());
    ajustarPedidos();
}
function voltarPedido() {
    $('div.pedidos').prepend($('div.pedidos span.pedido').last());
    ajustarPedidos();
}
function setAguardando() {
    var id = $('div.pedidos').find('span.pedido.selecionado').find("input.ids[type='hidden']").val();
    var situacao = $('#situacao_' + id).val();
    abrirPagina('vendaItemCrud.php?id=' + id + '&pronto=' + situacao + '&acao=alterar');
}
function setPronto() {
    var id = $('div.pedidos').find('span.pedido.selecionado').find("input.ids[type='hidden']").val();
    abrirPagina('vendaItemCrud.php?id=' + id + '&pronto=TRUE&acao=alterar');
}
function reconheceTeclasCozinha() {
    $('body').on('keyup', function (evt) {
        var key_code = evt.keyCode ? evt.keyCode :
                evt.charCode ? evt.charCode :
                evt.which ? evt.which :
                void 0;
        if (key_code == 65) {
            //a
            avancarPedido();
        } else if (key_code == 83) {
            //s
            setPronto();
        } else if (key_code == 68) {
            //d
            setAguardando();
        } else if (key_code == 70) {
            //f
            voltarPedido();
        }
    });
}
function carregaPedido() {
    var id = $('#id_maior').val();
    chamarPaginaAjax('vendaItemOpcao.php?id_maior=' + $('#id_maior').val(), '', function (r) {
        $('div.pedidos span.maior_id').after(r);
        ajustarPedidos();
        setTimeout(function () {
            carregaPedido();
        }, 10000);
    });
}


function atualizaMesa(id_mesa) {
    var situacao_atual = $('#situacao_' + id_mesa).val();
    chamarPaginaAjax('mesaSituacao.php?id=' + id_mesa, '', function (situacao) {
        if (situacao != situacao_atual) {
            chamarPaginaAjax('mesaOpcao.php?id=' + id_mesa, '', function (mesa) {
                $('#mesa_' + id_mesa).html(mesa);
            });
        }
//        setTimeout(function(){
//            atualizaMesa(id_mesa);
//        }, 5000);
    });
}
$(function () {
    $("#dialog").dialog({
        autoOpen: false,
        show: {
            effect: "blind",
            duration: 1000
        },
        hide: {
            effect: "explode",
            duration: 1000
        },
        width: 500
    });
});


function abrirFoto(foto) {
    $('#dialog').find('img').attr('src', foto);
    $('#dialog').dialog('open');
}

function filtrarCliente(valor) {
    if (valor == '') {
        $('table.table').find('tbody tr').show();
    } else {
        $('table.table').find('tbody tr').hide();
        $('table.table').find('tbody tr').each(function () {
            var nome = $(this).first('td').html().toUpperCase();
            if (nome.indexOf(valor.toUpperCase()) > -1) {
                $(this).show();
            }
        });
    }
}
function calcularTroco(elemento) {
    if (elemento.value == '') {
        elemento.value = '0.00';
    }
    var pago = parseFloat(elemento.value);
    var restante = parseFloat($('#restante').html());
    if (pago > restante) {
        var troco = formataFloat(pago - restante, 2);
    } else {
        var troco = formataFloat(0, 2);
    }

    $('#troco').html(troco);
}