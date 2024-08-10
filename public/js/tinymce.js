// public/js/tinymce.js
import tinymce from 'tinymce/tinymce';
import 'tinymce/themes/silver';
import 'tinymce/plugins/link';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/table';
import 'tinymce/plugins/code';
import 'tinymce/plugins/textcolor';
import 'tinymce/plugins/colorpicker';

document.addEventListener('DOMContentLoaded', function () {
    tinymce.init({
        selector: '#editor',
        plugins: 'link lists table code textcolor colorpicker',
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | forecolor backcolor',
        menubar: false
    });
});
