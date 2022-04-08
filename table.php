<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css" />
        <table id="tblSerial">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Serial Number</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
        <script>
        jQuery(document).ready(function($) {
            $("#tblSerial").DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": "pagination.php",
            } );
        } );
        </script>;