@props([
    'name' => 'description',
    'id' => null,
    'value' => '',
    'placeholder' => 'Provide detailed description, Vedic significance, and origin details...',
    'height' => 'min-h-[200px]',
])

@php
    $editorId = $id ?? 'wysiwyg_' . $name;
@endphp

@pushOnce('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <style>
        .wysiwyg-container .ql-toolbar.ql-snow {
            border-color: #cbd5e1 !important;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            background-color: #f8fafc;
            padding: 8px 12px !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }
        .wysiwyg-container .ql-container.ql-snow {
            border-color: #cbd5e1 !important;
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
            font-family: inherit !important;
            font-size: 0.875rem !important;
            background-color: #ffffff;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .wysiwyg-container:focus-within .ql-toolbar.ql-snow {
            border-color: #d97706 !important;
        }
        .wysiwyg-container:focus-within .ql-container.ql-snow {
            border-color: #d97706 !important;
            box-shadow: 0 0 0 1px #d97706;
        }
        .wysiwyg-container .ql-editor {
            min-height: 180px;
            max-height: 480px;
            overflow-y: auto;
            line-height: 1.65;
            color: #1e293b;
            padding: 12px 14px;
        }
        .wysiwyg-container .ql-editor.ql-blank::before {
            color: #94a3b8 !important;
            font-style: normal !important;
            font-size: 0.875rem;
            left: 14px;
            right: 14px;
        }
        .wysiwyg-container .ql-snow .ql-stroke {
            stroke: #475569 !important;
        }
        .wysiwyg-container .ql-snow .ql-fill {
            fill: #475569 !important;
        }
        .wysiwyg-container .ql-snow .ql-picker {
            color: #475569 !important;
            font-size: 0.8125rem;
        }
        .wysiwyg-container .ql-snow.ql-toolbar button:hover,
        .wysiwyg-container .ql-snow .ql-toolbar button:hover,
        .wysiwyg-container .ql-snow.ql-toolbar button.ql-active,
        .wysiwyg-container .ql-snow .ql-toolbar button.ql-active,
        .wysiwyg-container .ql-snow.ql-toolbar .ql-picker-label:hover,
        .wysiwyg-container .ql-snow.ql-toolbar .ql-picker-label.ql-active {
            color: #d97706 !important;
        }
        .wysiwyg-container .ql-snow.ql-toolbar button:hover .ql-stroke,
        .wysiwyg-container .ql-snow.ql-toolbar button.ql-active .ql-stroke {
            stroke: #d97706 !important;
        }
        .wysiwyg-container .ql-snow.ql-toolbar button:hover .ql-fill,
        .wysiwyg-container .ql-snow.ql-toolbar button.ql-active .ql-fill {
            fill: #d97706 !important;
        }
    </style>
@endPushOnce

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
@endPushOnce

<div x-data="{
        editorInstance: null,
        initialHtml: @js((string) old($name, $value ?? '')),
        initEditor() {
            if (this.editorInstance) return;

            const startQuill = () => {
                if (typeof Quill === 'undefined') {
                    setTimeout(startQuill, 60);
                    return;
                }

                if (this.editorInstance) return;

                const toolbarOptions = [
                    [{ 'header': [2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'clean']
                ];

                this.editorInstance = new Quill(this.$refs.quillDiv, {
                    theme: 'snow',
                    placeholder: '{{ addslashes($placeholder) }}',
                    modules: {
                        toolbar: toolbarOptions
                    }
                });

                // Populate initial content if present
                if (this.initialHtml && this.initialHtml.trim() !== '') {
                    this.editorInstance.clipboard.dangerouslyPasteHTML(this.initialHtml);
                }

                // Sync to hidden textarea on change
                this.editorInstance.on('text-change', () => {
                    const html = this.editorInstance.root.innerHTML;
                    const val = (html === '<p><br></p>' ? '' : html);
                    if (this.$refs.rawInput) {
                        this.$refs.rawInput.value = val;
                    }
                });

                // Ensure latest content is captured on form submission
                const form = this.$el.closest('form');
                if (form) {
                    form.addEventListener('submit', () => {
                        const html = this.editorInstance.root.innerHTML;
                        const val = (html === '<p><br></p>' ? '' : html);
                        if (this.$refs.rawInput) {
                            this.$refs.rawInput.value = val;
                        }
                    });
                }
            };

            startQuill();

            // Refresh layout when switching between tabs
            if (typeof this.activeTab !== 'undefined') {
                this.$watch('activeTab', (tab) => {
                    if (tab === 'details' && this.editorInstance) {
                        this.$nextTick(() => {
                            this.editorInstance.update();
                        });
                    }
                });
            }
        }
    }"
    x-init="initEditor()"
    class="wysiwyg-container w-full">

    <div wire:ignore>
        <div x-ref="quillDiv" class="{{ $height }}"></div>
    </div>

    <!-- Hidden native textarea submitted with standard form post -->
    <textarea x-ref="rawInput"
              name="{{ $name }}"
              id="{{ $editorId }}"
              class="hidden">{!! old($name, $value ?? '') !!}</textarea>
</div>
