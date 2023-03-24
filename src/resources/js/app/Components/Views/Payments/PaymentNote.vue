<template>
    <payment-note-data
        :row-data="rowData"
    />
</template>

<script>

export default {
    name: "PunchInGeolocation",
    props: {
        value: {},
        rowData: {},
    },
}
</script>

