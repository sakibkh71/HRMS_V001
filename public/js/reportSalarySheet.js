new Vue({

	el: "#mainDiv",
	data:{
		salary_month: '2017-11',
	},
	methods:{
	    myMonthPicker(){
        $('.myMonthPicker').datetimepicker({
            format: 'YYYY-MM',
            minViewMode: 'months',
            viewMode: 'months',
            pickTime: false,
        });
    	}, 
	}
});