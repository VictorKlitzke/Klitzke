<?php

?>

<div class="box-content">
    <h2>Buscar XML da NF-e</h2>
    <div class="list">
        <form class="form">

            <div class="form-group">
                <label>Numero do XML</label>
                <input type="file" id="xmlFile" accept=".xml" />
            </div>

            <div class="form-group">
                <button class="form-group" type="submit" id="" onclick="UploadXML()">Buscar</button>
            </div>
        </form>

        <div class="xml-product" id="xml-product"></div>
    </div>
</div>
