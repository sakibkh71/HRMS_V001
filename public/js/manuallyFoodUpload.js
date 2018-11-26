$(document).ready(function(){

var work = new Vue({
    el: '#foodID',
    data:{
      index:'',
      department_id:0,
      unit_id:0,
      branch_id:0,
      units:[],
      users:[],
      salary_type:'month',
      payRoll:[],
      adviceReport:[],
      errors:[],
      advice_type: 'bank',
      pdf_branch_id:0,
      pdf_department_id:0,
      pdf_unit_id:0,
      pdf_user_id:0,
      pdf_salary_month:0,
      cover_letter: null,
    },

    methods:{

      test(){
        console.log('Almighty ALLAH');
      },

      dataTableCall(id){
        $(id).dataTable({
          "destroy": true,
          "paging":   true,
          "searching": true,
          "info": true,
          "sDom": '<"dt-panelmenu clearfix"lfr>t<"dt-panelfooter clearfix"ip>',
        }); 
      },
      

      dataTableDestroy(id){
        $(id).dataTable().fnDestroy(); 
      },


      dataTableGenerate(id='#datatableCall'){
        vueThis = this;
        if(this.dataTable){
          setTimeout(function(){vueThis.dataTableCall(id);}, 5);
          this.dataTable = false;
        }else{
          this.dataTableDestroy(id);
          setTimeout(function(){vueThis.dataTableCall(id);}, 5);
        }
      },


      showMessage(data){
        new PNotify({
            title: data.title,
            text: data.message,
            shadow: true,
            addclass: 'stack_top_right',
            type: data.status,
            width: '290px',
            delay: 2000,
            icon: false,
        });
      },


      loadinShow(idClass){
        $(idClass).LoadingOverlay("show",{color:"rgba(0, 0, 0, 0)"});
      },


      loadinHide(idClass){
        $(idClass).LoadingOverlay("hide",{color:"rgba(0, 0, 0, 0)"});
      },


      myMonthPicker(){
          $('.myMonthPicker').datetimepicker({
              format: 'YYYY-MM',
              minViewMode: 'months',
              viewMode: 'months',
              pickTime: false,
          });
      },

      modal_open(form_id) {
        this.errors = [];

        $.magnificPopup.open({
            removalDelay: 300,
            items: {
                src: form_id
            },
            callbacks: {
                beforeOpen: function (e) {
                    var Animation = "mfp-zoomIn";
                    this.st.mainClass = Animation;
                }
            },
            midClick: true
        });
      },

    }

  });


  });



