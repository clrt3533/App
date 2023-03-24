<template>
    <div
         class="dropdown keep-inside-clicks-open dropdown-note-editor d-inline-block"  v-if="rowData.note">
        <button type="button"
                class="btn p-0 mb-1 primary-text-color"
                data-toggle="dropdown">
            <app-icon name="file-text" class="size-18"/>
        </button>
        <div class="dropdown-menu p-primary mt-1">
            <div>
                <p class="text-secondary">
                    {{ $t('payment_note') }}
                </p>
                <div class="note note-warning custom-scrollbar p-4 mb-5">
                    <p class="m-0"><label class="font-weight-bold"> <span v-html="rowData.note"></span></label></p>
                </div>

                <div class="text-right">

                    <a href="#"
                       class="btn btn-secondary"
                       @click.prevent="closeDropDown">
                        {{ $t('close') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "PaymentNoteData",
    props: {
        rowData: {},
    },

    methods: {
        closeDropDown() {
            $(".dropdown-menu").removeClass('show')
        },
    }

}
</script>

<style scoped lang="scss">
.dropdown {
    .dropdown-menu {
        width: 350px;

        .note {
            overflow-y: auto;
            max-height: 200px;
        }
    }
}
</style>