<div>
    @props(['model'])
    <div
        wire:ignore
        x-data="{
        value: @entangle($model),
        init() {
            let quill = new Quill(this.$refs.quill, { theme: 'snow' })

            quill.root.innerHTML = this.value

            quill.on('text-change', () => {
                this.value = quill.root.innerHTML
            })

            quill.on('selection-change', (range, oldRange, source) => {
                if (range === null && oldRange !== null) {
                    @this.set('{{ $model }}', this.value)
                }
            })

            @this.on('resetQuill', () => {
                quill.root.innerHTML = ''
                this.value = ''
            })
        },
    }"
        class="max-w-2xl w-full"
    >
        <div x-ref="quill"></div>
    </div>
</div>
