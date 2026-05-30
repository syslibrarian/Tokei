<?php

if (isset($_POST['demoText'])) {
    file_put_contents('../example.json', $_POST['demoText']);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Editor Test</title>
        <meta charset="utf-8">
        <script type="importmap">
            {
                "imports": {
                    "@editorjs/editorjs": "./js/3rdParty/Editor/editorjs.js",
                    "@editorjs/header": "./js/3rdParty/Editor/header.js",
                    "@editorjs/paragraph": "./js/3rdParty/Editor/paragraph.js",
                    "@editorjs/list": "./js/3rdParty/Editor/editorjs-list.js",
                    "@editorjs/code": "./js/3rdParty/Editor/code.js",
                    "@editorjs/marker": "./js/3rdParty/Editor/marker.js",
                    "@editorjs/inline-code": "./js/3rdParty/Editor/inline-code.js",
                    "@editorjs/quote": "./js/3rdParty/Editor/quote.js",
                    "@editorjs/underline": "./js/3rdParty/Editor/underline.js"
                }
            }
        </script>

        <script src="./js/Editor.js" type="module" defer></script>
    </head>
    <body>
        <form id="editorTest" method="POST" target="/editor.php">
            <div id="editor"></div>
            <input type="hidden" name="demoText" id="editorText">
            <input type="submit" name="Absenden">
        </form>
    </body>
</html>