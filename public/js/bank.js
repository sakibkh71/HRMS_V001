 $(document).on('ready',function(){

  new Vue({
    el: '#bank_list',
    data:{
      banks:[],
      bank:[],
      errors:[]
    },

    mounted(){
      this.getBanks();
    },

    // watch:{
    //   banks:function(){
    //     setTimeout(function() {
    //         this.dataTableCall();
    //     }, 1000);
    //   }

    // },

    // destroyed(){
    //   this.dataTableCall();
    // },

    // beforeDestroy(){
    //   this.dataTableCall();
    // },


    methods:{

      dataTableCall(){
        $('#datatableCall').dataTable({
          "paging":   true,
          "searching": true,
          "info": true,
          "sDom": '<"dt-panelmenu clearfix"lfr>t<"dt-panelfooter clearfix"ip>',
        }); 
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


      modal_open(form_id) {
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


      getBanks(){
        $('#datatableCall').LoadingOverlay("show",{color:"rgba(0, 0, 0, 0)"});

        axios.get('/bank/index').then((response) => {
          this.banks = response.data;
        
        }).catch((error)=>{
          alert('please reload page.');
        });

        $('#datatableCall').LoadingOverlay("hide");
      },


      addBank(e){
        var formData = new FormData(e.target);
        var form_id = e.target.id;
        // alert(form_id);
        $('#'+form_id).LoadingOverlay("show",{color:"rgba(0, 0, 0, 0)"});

        axios.post('/bank/add',formData).then((response) => {
          this.banks.push(response.data.data);
          jQuery(".mfp-close").trigger("click");
          this.showMessage(response.data);
          $('#'+form_id).LoadingOverlay("hide",{color:"rgba(0, 0, 0, 0)"});

        }).catch((error)=>{
          $('#'+form_id).LoadingOverlay("hide",{color:"rgba(0, 0, 0, 0)"});

          if(error.response.status == 500 || error.response.data.status == 'danger'){
              var error = error.response.data;
              this.showMessage(error);
          }else if(error.response.status == 422){
              this.errors = error.response.data;
          }
        });
      },


      editBank(id,model_id){
        $('#bank').LoadingOverlay("show",{color:"rgba(0, 0, 0, 0)"});

        axios.get('/bank/edit/'+id).then((response) => {
          $('#bank').LoadingOverlay("hide",{color:"rgba(0, 0, 0, 0)"});
          this.bank = response.data.data;
          this.modal_open(model_id);

        }).catch((error)=>{
          $('#bank').LoadingOverlay("hide",{color:"rgba(0, 0, 0, 0)"});
          if(error.response.status == 500 || error.response.data.status == 'danger'){
              var error = error.response.data;
              this.showMessage(error);
          }else if(error.response.status == 422){
              this.errors = error.response.data;
          }
        });
      },


      updateBank(e){
        var formData = new FormData(e.target);
        var form_id = e.target.id;

        $('#bank').LoadingOverlay("show",{color:"rgba(0, 0, 0, 0)"});

        axios.post('/bank/edit',formData).then((response) => {
          this.getBanks();
          jQuery(".mfp-close").trigger("click");
          this.showMessage(response.data);
          $('#bank').LoadingOverlay("hide",{color:"rgba(0, 0, 0, 0)"});
        }).catch((error)=>{
          $('#bank').LoadingOverlay("hide",{color:"rgba(0, 0, 0, 0)"});
          if(error.response.status == 500 || error.response.data.status == 'danger'){
              var error = error.response.data;
              this.showMessage(error);
          }else if(error.response.status == 422){
              this.errors = error.response.data;
          }
        });
      },


      deleteBank(id,index_id){

       // var ck = confirm("Are you sure delete this?");
       //  if(ck == false){
       //      return false;
       //  }

       var vueThis = this;

       swal({
          title: "Are you sure?",
          text: "You will not be able to recover this imaginary data!",
          // type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, delete it!",
          closeOnConfirm: false
        },
        function(){
            $('body').LoadingOverlay("show");    
            axios.delete('/bank/delete/'+id).then((response) => {
              vueThis.banks.splice(index_id,1);
              $.LoadingOverlay("hide");
              // vueThis.showMessage(error.response.data);
              swal("Deleted!", "Your data file has been deleted.", "success");

            }).catch((error)=>{
              $.LoadingOverlay("hide");
              // vueThis.showMessage(error.response.data);
              swal("Cancelled", "Your data not deleted.", "error");
            });
        });

      },

      changeStatus(e,id){
        $.LoadingOverlay("show");
        var status = e.target.getAttribute('status');
        var status2;

        if(status == 1){
          status2 = 0;
        }else{
          status2 = 1;
        }
        
        axios.post('/bank/edit',{'id':id,'status':status2}).then((response) => {
          e.target.setAttribute('status',status2);

          if(status == 0){
            e.target.classList.remove("text-primary");
            e.target.classList.add("text-danger");
            e.target.text = "Inactive";            
          }else{
            e.target.classList.remove("text-danger");
            e.target.classList.add("text-primary");
            e.target.text = "Active";
          }
          $('body').LoadingOverlay("hide");
          this.showMessage(response.data);
        }).catch((error) => {

          if(error.response.status == 500 || error.response.data.status == 'danger'){
                this.showMessage(error.response.data);
            }
          $('body').LoadingOverlay("hide");
        });
      },



    }

  });


});