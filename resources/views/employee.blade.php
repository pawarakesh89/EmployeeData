<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Form</title>

    <!-- Include jQuery and Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Employee Form</h2>
        <form id="employeeForm" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstName" name="firstName" required>
            </div>
            <div class="mb-3">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="lastName" required>
            </div>
            <div class="mb-3">
                <label for="joiningDate" class="form-label">Joining Date</label>
                <input type="date" class="form-control" id="joiningDate" name="joiningDate" required>
            </div>
            <div class="mb-3">
                <label for="profileImage" class="form-label">Profile Image (max 2MB)</label>
                <input type="file" class="form-control" id="profileImage" name="profileImage">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <h2 class="mt-5">Employee List</h2>
        <input type="date" id="filterStartDate" placeholder="Start Date">
        <input type="date" id="filterEndDate" placeholder="End Date">
        <button id="filterBtn" class="btn btn-secondary">Filter</button>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Employee Code</th>
                    <th>Profile Image</th>
                    <th>Full Name</th>
                    <th>Joining Date</th>
                </tr>
            </thead>
            <tbody id="employeeTable"></tbody>
        </table>

        <button id="prevPage" class="btn btn-secondary">Previous</button>
        <button id="nextPage" class="btn btn-secondary">Next</button>
    </div>

    <script>
        $(document).ready(function() {
            var currentPage = 1;

            // form submission using ajax
            $('#employeeForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: '/employees',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert('Employee added successfully');
                        loadEmployees(currentPage);
                        $('#employeeForm')[0].reset();
                    }
                });
            });

            // Load employee data with pagination
            function loadEmployees(page) {
                $.get(`/employees?page=${page}`, function(data) {
                    $('#employeeTable').empty();
                    $.each(data.data, function(index, employee) {
                        $('#employeeTable').append(`
                    <tr>
                        <td>${employee.emp_code}</td>
                        <td><img src="/storage/${employee.profile_image}" width="50" /></td>
                        <td>${employee.full_name}</td>
                        <td>${employee.joining_date}</td>
                    </tr>
                `);
                    });
                });
            }

            // Filter employees by date range
            $('#filterBtn').on('click', function() {
                const startDate = $('#filterStartDate').val();
                const endDate = $('#filterEndDate').val();

                $.get(`/employees?startDate=${startDate}&endDate=${endDate}`, function(data) {
                    $('#employeeTable').empty();
                    $.each(data, function(index, employee) {
                        $('#employeeTable').append(`
                    <tr>
                        <td>${employee.emp_code}</td>
                        <td><img src="/storage/${employee.profile_image}" width="50" /></td>
                        <td>${employee.full_name}</td>
                        <td>${employee.joining_date}</td>
                    </tr>
                `);
                    });
                });
            });

            // Pagination controls
            $('#prevPage').on('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    loadEmployees(currentPage);
                }
            });

            $('#nextPage').on('click', function() {
                currentPage++;
                loadEmployees(currentPage);
            });

            // Load initial employee list
            loadEmployees(currentPage);
        });
    </script>
</body>

</html>
