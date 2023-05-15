
    const tableClassroomStaff = $("#staffs-classroom")

    const classroomId = $("#classroom_id")

    const setClassroomId = $("input[name='classroom_id']")

    const columns = [
        {"data": "staff.id" },
        {"data": "staff.name"},
        {"data": "class_staff"},
        {"data": 'action'},

    ];


    var datatableStaff = (table,url)  =>{
       return table.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: url
            },
            columns: columns,
        });
    }

    var datatableStudentRoom = datatableStaff(
                tableClassroomStaff,
                route('datatable.assign-classroom-staff'),
            )
