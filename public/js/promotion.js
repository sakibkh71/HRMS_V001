Vue.component('select2', {
    props: ['options', 'value'],
    template: '<select><slot></slot></select>',
    mounted: function () {
        var vm = this
        $(this.$el)
          .val(this.value)
          // init select2
          .select2({ data: this.options })
          // emit event on change.
          .on('change', function () {
            vm.$emit('input', this.value)
          })
    },
    watch: {
        value: function (value) {
          // update value
          $(this.$el).val(value)
        },
        options: function (options) {
          // update options
          $(this.$el).select2({ data: options })
        }
    },
    destroyed: function () {
        $(this.$el).off().select2('destroy')
    }
})

new Vue({
	el: '#mainDiv',
	data:{
		hdn_id: '',
		formType: "",
		allData: [],
		user_id: 0,
		from_designation: '',
		from_designation_id: '',
		to_designation_id: '',
	    users: [],
	    designation_select2: [],
	    from_unit: '',
	    from_unit_id: '',
	    unit_select2: [],
	    from_branch: '',
	    from_branch_id: '',
	    branch_select2: [],
	    from_supervisor: '',
	    from_supervisor_id: 0,
	    supervisor_select2: [],
	    to_supervisor_id: '',

	    edit_user_id:'',
	    edit_formType: "",
		edit_from_designation: '',
		edit_from_designation_id: '',
		edit_to_designation_id: '',
		edit_designation_select2: [],
		edit_from_unit: '',
        edit_from_unit_id: '',
		edit_to_unit_id: '',
		edit_unit_select2: [],
		edit_from_branch: '',
		edit_from_branch_id: '',
        edit_to_branch_id: '',
		edit_branch_select2: [],
		edit_from_supervisor: '',
		edit_from_supervisor_id: 0,
		edit_supervisor_select2: [],
		edit_to_supervisor_id: '',
		edit_effective_date: '',
		edit_remarks: '',
	},
	mounted(){
		this.getAllData();  //call method
	},
	watch:{
		user_id: function(id){
			axios.get('/promotion/getSingelUser/'+id).then(response => {
                this.from_designation = response.data.multi_values.designation.designation_name+'-('+response.data.multi_values.designation.level.level_name+')-('+response.data.multi_values.designation.department.department_name+')';
                this.from_designation_id = response.data.multi_values.designation.id;
            	this.designation_select2 = response.data.to_designation;
            	this.from_unit = (response.data.multi_values.unit)?(response.data.multi_values.unit.unit_name+'-('+response.data.multi_values.unit.department.department_name+')'):'';
            	this.from_unit_id = (response.data.multi_values.unit)?(response.data.multi_values.unit.id):'';
            	this.from_branch = (response.data.multi_values.branch)?(response.data.multi_values.branch.branch_name):'';
            	this.from_branch_id = (response.data.multi_values.branch)?(response.data.multi_values.branch.id):'';
            	this.from_supervisor = (response.data.multi_values.supervisor)?(response.data.multi_values.supervisor.first_name+' '+response.data.multi_values.supervisor.last_name):'';
            	this.from_supervisor_id = (response.data.multi_values.supervisor)?(response.data.multi_values.supervisor.id):'0';
            	this.supervisor_select2 = response.data.to_supervisor;
            });
		},
		to_designation_id: function(id){
			axios.get('/get-unit-by-designation-id/'+id).then(response => {
                this.unit_select2 = response.data;
            });
		},
		// edit_to_designation_id: function(id){
		// 	axios.get('/get-unit-by-designation-id/'+id).then(response => {
  //               this.edit_unit_select2 = response.data;
  //           });
  //               alert('iddd4');
		// }
	},
	methods:{
		getAllData(){
			axios.get('/promotion/getPromotionsData').then(response => this.allData = response.data);
			axios.get('/get-branches').then(response => this.branch_select2 = response.data);
			axios.get('/get-employee').then(response => this.users = response.data);
		},
		savePromotion: function(formId){

    		var formData = $('#'+formId).serialize();

            axios.post('/promotion/add', formData)
            .then((response) => { 

                this.getAllData();  //call method

                $('#create-form-errors').html('');
                document.getElementById("modal-close-btn").click();

                new PNotify({
                    title: response.data.title+' Message',
                    text: response.data.message,
                    shadow: true,
                    addclass: 'stack_top_right',
                    type: response.data.title,
                    width: '290px',
                    delay: 1500
                });
            })
            .catch((error) => {
            
                if(error.response.status != 200){ //error 422
                
                    var errors = error.response.data;

                    var errorsHtml = '<div class="alert alert-danger"><ul>';
                    $.each( errors , function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></di>';
                    $( '#create-form-errors' ).html( errorsHtml );
                }
            });
        },
        editData: function(id, index){

        	this.unitIndex = index;
            this.hdn_id = id;

            axios.get('/get-branches').then(response => this.edit_branch_select2 = response.data);

            var userId = this.allData[index].user.id;

            axios.get('/promotion/getSingelUser/'+userId).then(response => {
                this.edit_designation_select2 = response.data.to_designation;
                this.edit_supervisor_select2 = response.data.to_supervisor;
            });
            
            this.edit_user_id = this.allData[index].user.first_name+' '+this.allData[index].user.last_name;
            this.edit_formType = this.allData[index].promotion_type;
            this.edit_from_designation = this.allData[index].prev_designation.designation_name+'-('+this.allData[index].prev_designation.level.level_name+')-('+this.allData[index].prev_designation.department.department_name+')';
			this.edit_from_unit = (this.allData[index].prev_unit)?this.allData[index].prev_unit.unit_name:'';
			this.edit_from_branch = (this.allData[index].prev_branch)?this.allData[index].prev_branch.branch_name:'';
			this.edit_from_supervisor = (this.allData[index].prev_supervisor)?this.allData[index].prev_supervisor.first_name:'';
			this.edit_effective_date = this.allData[index].transfer_effective_date;
			this.edit_remarks = this.allData[index].remarks;

            this.edit_to_designation_id = this.allData[index].current_designation.id;
            //console.log(this.edit_to_designation_id);

            axios.get('/get-unit-by-designation-id/'+this.edit_to_designation_id).then(response => {
                this.edit_unit_select2 = response.data;
            });

            this.edit_to_unit_id = this.allData[index].current_unit.id;
            this.edit_to_branch_id = (this.allData[index].current_branch)?this.allData[index].current_branch.id:'';
            this.edit_to_supervisor_id = (this.allData[index].current_supervisor)?this.allData[index].current_supervisor.id:'';

            $.LoadingOverlay("show");

            setTimeout(function(){
               $('#modalEdit').modal('show');
               $.LoadingOverlay("hide");
            }, 1000);
        },
        onClickEditDesignation(){

            var val = this.edit_to_designation_id>0?this.edit_to_designation_id:0;
            axios.get('/get-unit-by-designation-id/'+val).then(response => {
                this.edit_unit_select2 = response.data;
            });
        },
        updateData: function(updateFormId){
            
            var formData = $('#'+updateFormId).serialize();

            axios.post('/promotion/edit', formData)
            .then(response => { 
               
                $('#edit-form-errors').html('');
                document.getElementById("modal-edit-close-btn").click();
                
                this.getAllData();  //call method

                new PNotify({
                    title: response.data.title+' Message',
                    text: response.data.message,
                    shadow: true,
                    addclass: 'stack_top_right',
                    type: response.data.title,
                    width: '290px',
                    delay: 1500
                });

                console.log(response.data.data);
            })
            .catch( (error) => {
                var errors = error.response.data;

                var errorsHtml = '<div class="alert alert-danger"><ul>';
                $.each( errors , function( key, value ) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></di>';
                $( '#edit-form-errors' ).html( errorsHtml );
            });
        }
	}
});
