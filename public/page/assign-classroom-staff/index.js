

    const tableStaff = $("#staffs")

    const tableClassroomStaff = $("#staffs-classroom")

    const btnStore = $("#assign-classroom-store")

    const btnDestroy = $("#assign-classroom-delete")

    const classroomId = $("#classroom_id")

    const setClassroomId = $("input[name='classroom_id']")

    const columns = [
        {"data": "id"},
        {"data": "id" },
        {"data": "name"},
    ];


    var selectedStaffStore = new Set()
    var selectedStaffDelete = new Set()
    var classroomSetData = {}

    var hideBtn = (id,data) =>{data.size >= 1 ? id.show() : id.hide() };



    var selectedStore = (e) => {
        let isSelected = $(e).is(":checked");
        let valSelected = parseInt($(e).val());
        if (isSelected) {
            tableStaff.find('input[name=id]').prop('checked',false)
            tableStaff.find('.selected').removeClass();
            $(e).prop('checked',true)

            $(e).parent().parent().addClass("selected")
            selectedStaffStore.clear()
            selectedStaffStore.add(valSelected)
        } else {
            $(e).parent().parent().removeClass("selected")
            selectedStaffStore.delete(valSelected)
        }
        hideBtn(btnStore,selectedStaffStore)
    }

    var selectedDestroy = (e) => {
        let isSelected = $(e).is(":checked");
        let valSelected = parseInt($(e).val());
        if (isSelected) {
            // tableClassroomStaff.find('input[name=id]').prop('checked',false)
            // tableClassroomStaff.find('.selected').removeClass();
            // $(e).prop('checked',true)

            $(e).parent().parent().addClass("selected")
            // selectedStaffDelete.clear()
            selectedStaffDelete.add(valSelected)
        } else {
            $(e).parent().parent().removeClass("selected")
            selectedStaffDelete.delete(valSelected)
        }
        hideBtn(btnDestroy,selectedStaffDelete)
    }

    var reloadajax = (e) => {
        setDataClassroomID()
        e.ajax.reload(null, false)
    }




    var setDataClassroomID = ()=>{
        let classroom_id = classroomId.find(":selected").val()
        setClassroomId.val(classroom_id)
        classroomSetData.classroom_id = classroom_id

    }

    var datatableStudent = (table,url,dataSelected,selectedRowFun)  =>{
        setDataClassroomID()
       return table.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                data: function(d) {
                    return $.extend(d, classroomSetData)
                },
                url: url
            },
            columns: columns,
            'columnDefs': [{
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function(data, type, full, meta) {

                    let isChecked = dataSelected.has(data) ? 'checked' : '';
                    return `<input type="checkbox" name="id" onclick="${selectedRowFun}(this)" value="${data}" ${isChecked} />`
                }
            }],
            'order': [2, 'asc'],
            createdRow: function(row, data, dataIndex) {
                if (dataSelected.has(data.id)) {
                    $(row).addClass('selected');
                } else {
                    $(row).removeClass('selected');
                }
            }
        });
    }


    var appenDataStaff = (data,btn) => {
        for (var it = data.values(), val = null; val = it.next().value;) {
            btn.append(`<input type="hidden" name="id[]" value="${val}" /> `)
        }
    }

    var datatableStaff = datatableStudent(
        tableStaff,
        route('datatable.assign-staffs'),
        selectedStaffStore,
        'selectedStore'
        )

    var datatableStudentRoom = datatableStudent(
                tableClassroomStaff,
                route('datatable.assign-classroom-staff'),
                selectedStaffDelete,
                'selectedDestroy'
            )




    hideBtn(btnStore,selectedStaffStore)
    hideBtn(btnDestroy,selectedStaffDelete)

    btnStore.click(()=>{
        appenDataStaff(selectedStaffStore,btnStore)
    });

    btnDestroy.click(()=>{
        appenDataStaff(selectedStaffDelete,btnDestroy)
    });


    classroomId.change(function() {
        reloadajax(datatableStudentRoom)
    });
