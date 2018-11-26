<template>
<div>
    <div class="col-md-3 mb15" v-for="allowance in otherAllowances">
        <div class="checkbox-custom mb5 pull-left">

            <input :id="allowance.id" type="checkbox" :name="'salary_info['+allowance.id+'][id]'" :value="allowance.id" checked="checked">

            <label :for="allowance.id" v-text="(allowance.salary_info_amount_status==0)?allowance.salary_info_name+' (%) ':allowance.salary_info_name+' ($) '"></label>
        </div>

        <select class="col-md-2 form-control input-sm mb5" :name="'salary_info['+allowance.id+'][type]'">
            <option value="percent" :selected="allowance.salary_info_amount_status ==0">Percent</option>
            <option value="fixed" :selected="allowance.salary_info_amount_status ==1">Fixed</option>
        </select>

        <input type="number" :name="'salary_info['+allowance.id+'][amount]'" :value="allowance.salary_info_amount" class="col-md-2 form-control input-sm mb5">

        <input type="text" :name="'salary_info['+allowance.id+'][effective_date]'" class="datepicker2 form-control input-sm">
    </div>
</div>
</template>

<script>
    export default{
        props: ['otherAllowances'],
    }    
</script>