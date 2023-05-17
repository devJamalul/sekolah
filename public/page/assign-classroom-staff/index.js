
    const tableClassroomStaff = $("#staffs-classroom")

    const classroomId = $("#classroom_id")

    const setClassroomId = $("input[name='classroom_id']")

    const columns = [
        {"data": "nik" },
        {"data": "nip" },
        {"data": "name"},
        {"data": "gender"},
        {"data": "staff_class"},
        {"data": 'action'},

    ];


    var datatableStaff = (table,url)  =>{
       return table.DataTable({
            processing: true,
            serverSide: true,
            // searching: false,
            ajax: {
                url: url
            },
            columns: columns,
        });
    }
    var getClassroom = function(id,setClassromList,idClassroomOld = 0){
        var result = null;
        $.get(route('get-classroom')+'?academy_year_id='+id,function(data){
            setClassromList.empty();
            setClassromList.append('<option value="">-- PILIH --</option>');

            for (let i = 0; i < data.classrooms.length; i++) {
                // let isSelected = currentClassroom == data.classrooms[i].id ? 'selected="true"' : '';


                if(idClassroomOld>0 && idClassroomOld == data.classrooms[i].id){
                    continue;
                }

                setClassromList.append(`
                <option value="${data.classrooms[i].id}">
                 ${data.classrooms[i].grade.grade_name} -
                 ${data.classrooms[i].name}</option>
                `);
            }
            classroomList = data.classrooms
            result = data;
        });
        return result;
    }
    $("#academy_year_ubah").change(function(){
        let setClassroomOld = $("#classroom_old").val()
        let setListClassroom = $("#classroom-modal-ubah")
        getClassroom($(this).val(),setListClassroom,setClassroomOld)
    })



    var datatableStudentRoom = datatableStaff(
                tableClassroomStaff,
                route('datatable.assign-classroom-staff'),

                )



    var modalAssignClass = function(e){
        let data = $(e).data();
        if(data.btn=='Ubah'){
            $('#metode').attr('name','_method')
            $('#metode').val('DELETE')
        }else{
            $('#metode').attr('name','_method')
            $('#metode').val('POST')
        }


        $('#form-modal').attr('action', data.url)
        $("#classroom_old").val(data.classroomId)
        $("#btn-modal").html(data.btn)
        $("#staff_id_modal").val(data.staffId)
        $("#assingclassroom-modal-label").html(data.label)
        $("#modalAssignClass").modal()
    };
