<?php
/**
 * @author Daniel Criado Casas<dani.criado.casas@gmail.com>
 */
?>
<script type="text/javascript">/*<![CDATA[*/
    require(["dijit/layout/TabContainer", "dijit/layout/ContentPane", "dojo/domReady!"], function(TabContainer, ContentPane) {
        var tcProcessing = new TabContainer({
            style: "height: 100%; width: 100%;"
        }, "processingmanager");

        var cpGenerate = new ContentPane({
            title: "Generació"
        });

        cpGenerate.set("href", "lib/plugins/processingmanager/generate.html");
        tcProcessing.addChild(cpGenerate);

        var cpImport = new ContentPane({
            title: "Importació"
        }, "import");
        //cpImport.set("href", "lib/plugins/processingmanager/import.php");
        tcProcessing.addChild(cpImport);

        var cpGalery = new ContentPane({
            title: "Galeria"
        });
        cpGalery.set("href", "lib/plugins/processingmanager/galery.html");
        tcProcessing.addChild(cpGalery);


        tcProcessing.startup();
    });
    /*!]]>*/</script>

<script>
    require([
        "dojo/json"
                , 'dojo/_base/connect'
                , 'dojo/dom'
                , 'dojo/dom-construct'
                , 'dijit/registry'
                , 'dojo/parser'
                , 'dojo/domReady!'
                , 'dijit/form/Button'
                , 'dojox/form/Uploader'
                , "dijit/layout/TabContainer"
                , "dijit/layout/ContentPane"
                , "dojo/domReady!"
    ], function(JSON, cn, dom, domConst, registry, parser) {
        parser.parse();
        var uploader = registry.byId("uploader");

        var handleUpload = function(upl, node) {
            cn.connect(upl, "onComplete", function(data) {
                //var json = JSON.parse(data, false);
                var div = domConst.create('div', {className: 'thumb'});
                var span = domConst.create('span', {className: 'thumbbk'}, div);
                span.innerHTML = "<p> type: " + data[0].type + "</p>"
                        + "<p> value: " + JSON.stringify(data[0].value) + "</p>"

                node.appendChild(div);
            });
        };
        
        
        
        var handleDnD = function(domnode, uploader) {
            if (uploader.addDropTarget && uploader.uploadType == 'html5') {
                domConst.create('div', {innerHTML: 'Pots arrosegar el fitxer fins aquí'}, domnode, 'last');
                uploader.addDropTarget(domnode);
            }
        };

        cn.connect(registry.byId("remBtn"), "onClick", uploader, "reset");
        //cn.connect(registry.byId("submit"), "onClick", uploader, "submit");
        handleUpload(uploader, dom.byId('response'));
        if (require.has('file-multiple')) {
            handleDnD(uploader.domNode.parentNode, uploader);
        }
    });
</script> 

<div style="width: 800; height: 600px">
    <div id="processingmanager"></div>
    <div id="import" >
        <form id="f1" name="f1" method="post" action="/dokuwiki/lib/plugins/ajaxcommand/ajax.php?call=save_pde_algorithm" enctype="multipart/form-data">
            <fieldset>
                <legend>Formulari per carregar algorismes de Processing</legend>
                Fitxer: <div data-dojo-type="dojox/form/Uploader" id="uploader" data-dojo-props="name:'uploadedfile',showInput:'before',isDebug:true">Selecciona</div>
                <div>
                <label for="id_field">Id: </label>
                <input title="Introdueix una sola paraula" type="text" name="id" id="id_field" value="idDani" aria-label="album" />
                </div>
                <div>
                <label for="nom_field">Nom: </label>
                <input type="text" name="nom" id="nom_field" value="Dani" aria-label="nom" />
                </div>
                <div>
                <label for="descripcio_field">Descripció: </label>
                <textarea id="descripcio_field" name="descripcio" aria-label="descripcio">Descripcio dani</textarea>
                </div>
                <input type="button" id="remBtn" label="Neteja" data-dojo-type="dijit/form/Button" />
                <input type="submit" id="submit" label="Carrega" data-dojo-type="dijit/form/Button" />
            </fieldset>
        </form>
        <div id="response"></div>


    </div>
</div>