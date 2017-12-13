<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>Editable Table</title>
    <link href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" rel="stylesheet" />
  </head>

  <body>

    <table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>First name</th>
                <th>Last name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Start date</th>
                <th>Salary</th>
                <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td>{{ user.id }}</td>
                <td><a href="#">{{ user.firstname }} {{ user.lastname }}</a></td>
                <td class="hidden-phone">{{ user.email }}</td>
                <td>{{ user.phone }}</td>
                <td><span class="label label-info label-mini">Due</span></td>
                <td>
                    <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>
                    <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                    <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                </td>
            </tr>
        </tfoot>
        <tbody>
        <tr >
            <td>1</td>
            <td>Airi</td>
            <td>Satou</td>
            <td>Accountant</td>
            <td>Tokyo</td>
            <td>28th Nov 08</td>
            <td>$162,700</td>
            <td>$dgf,700</td>
        </tr>
        <tr >
            <td><i class="fa fa-bullhorn"></i>2</td>
            <td>Angelica</td>
            <td>Ramos</td>
            <td>Chief Executive Officer (CEO)</td>
            <td>London</td>
            <td>9th Oct 09</td>
            <td>$1,200,000</td>
            <td>$1,dsf,000</td>
        </tr>
        </tbody>
    </table>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "server_processing"
                },
                "deferLoading": 57,
                "columnDefs": [
                        { 
                            "name": "Action",
                            "targets": 7,
                            "render": function ( data, type, full, meta ) {
                                return '<a class="btn btn-info" data-sid="" href="">Edit</a>';
                               }
                        }
                    ]
            } );
        } );
    </script>
  </body>
</html>
