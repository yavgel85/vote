<div x-data="{ value: @entangle($attributes->wire('model')) }" x-init="tinymce.init({
    target: $refs.tinymce,
    themes: 'modern',
    theme_advanced_resizing: true,
    height: 200,
    menubar: false,
    plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table paste code help wordcount'
    ],
    toolbar: 'undo redo | formatselect | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
    setup: function(description) {
        description.on('blur', function(e) {
            value = description.getContent()
        })

        description.on('init', function(e) {
            if (value != null) {
                description.setContent(value)
            }
        })

        function putCursorToEnd() {
            description.selection.select(description.getBody(), true);
            description.selection.collapse(false);
        }

        $watch('value', function(newValue) {
            if (newValue !== description.getContent()) {
                description.resetContent(newValue || '');
                putCursorToEnd();
            }
        });
    }
})" wire:ignore>
    <div>
        <input x-ref="tinymce" type="textarea" {{ $attributes->whereDoesntStartWith('wire:model') }}>
    </div>
</div>
