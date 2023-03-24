<template>
    <div class="content-wrapper">
        <app-breadcrumb :page-title="$t('client_statement')"/>

        <app-table :id="tableId" :options="options"/>
    </div>
</template>

<script>
    import ClientStatementMixin from '../../../Mixins/ClientStatementMixin';

    export default {
        name: 'ClientStatementReport',
        mixins: [ClientStatementMixin]
    }
</script>