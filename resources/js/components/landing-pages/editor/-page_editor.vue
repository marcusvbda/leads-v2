<template>
    <div class="card">
        <div class="card-body">
            <div id="gjs"></div>
        </div>
    </div>
</template>
<script>
import grapesjs from "grapesjs";
import "grapesjs/dist/css/grapes.min.css";

export default {
    created() {
        let editor = grapesjs.init({
            container: "#gjs",
            components: '<div class="txt-red">Hello world!</div>',
            style: ".txt-red{color: red}"
        });
        console.log(editor);
    }
};
</script>
