import $ from 'jquery';
window.$ = $;
window.jQuery = $;
import Sortable from 'sortablejs';
window.Sortable = Sortable;
import 'bootstrap';
import initSelect2 from 'select2';
initSelect2(window, $);

function resetMultipleSelectPlaceholder(el) {
    $(el).next('.select2-container')
         .find('.select2-search__field')
         .attr('placeholder', 'Add an option');
}

document.addEventListener('DOMContentLoaded', function () {
    $('.select2:not([multiple])').select2();

    $('.select2[multiple]').each(function () {
        $(this).select2({ placeholder: 'Add an option' })
               .on('select2:select select2:unselect', () => resetMultipleSelectPlaceholder(this));
        resetMultipleSelectPlaceholder(this);
    });
});
