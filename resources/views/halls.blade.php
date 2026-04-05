<!DOCTYPE html>
<html>
<head>
    <title>Exam Hall Assignment</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        input, button { padding: 8px; margin: 5px; }
        table { border-collapse: collapse; margin-top: 15px; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .hall { margin-top: 30px; }
        .unassigned { background-color: #ffe5e5; }
    </style>
</head>
<body>

<h2>Exam Hall Distribution</h2>

<!-- Inputs -->
<label>Number of Halls:</label>
<input type="number" id="halls" placeholder="Enter halls">

<label>Capacity per Hall:</label>
<input type="number" id="capacity" placeholder="Enter capacity">

<button onclick="assignHalls()">Run Distribution</button>

<hr>

<!-- Results -->
<div id="results"></div>

<script>
function assignHalls() {
    let halls = document.getElementById('halls').value;
    let capacity = document.getElementById('capacity').value;

    fetch(`/assign-exam-halls?halls=${halls}&capacity=${capacity}`)
        .then(response => response.json())
        .then(data => {

            let output = "";

            // Assigned halls
            for (let hall in data.assigned_halls) {
                output += `<div class="hall">`;
                output += `<h3>Hall ${hall}</h3>`;
                output += `<table>`;
                output += `<tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Father Name</th>
                            <th>Last Name</th>
                            <th>Hall</th>
                          </tr>`;

                data.assigned_halls[hall].forEach(student => {
                    output += `<tr>
                        <td>${student.id}</td>
                        <td>${student.firstName}</td>
                        <td>${student.fatherName}</td>
                        <td>${student.lastName}</td>
                        <td>${student.examHall}</td>
                    </tr>`;
                });

                output += `</table></div>`;
            }

            // Unassigned students
            if (data.unassigned_students.length > 0) {
                output += `<div class="hall">`;
                output += `<h3 style="color:red;">Unassigned Students</h3>`;
                output += `<table class="unassigned">`;
                output += `<tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Father Name</th>
                            <th>Last Name</th>
                            <th>Hall</th>
                          </tr>`;

                data.unassigned_students.forEach(student => {
                    output += `<tr>
                        <td>${student.id}</td>
                        <td>${student.firstName}</td>
                        <td>${student.fatherName}</td>
                        <td>${student.lastName}</td>
                        <td>None</td>
                    </tr>`;
                });

                output += `</table></div>`;
            }

            document.getElementById('results').innerHTML = output;
        });
}
</script>

</body>
</html>