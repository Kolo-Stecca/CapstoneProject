
<!-- <?php

require('../model/database.php');  
require('../model/projects.php');

if (isset($_GET['client_id'])) {
    $client_id = filter_input(INPUT_GET, 'client_id', FILTER_VALIDATE_INT);

    if ($client_id) {
        $projects = get_projects_by_client($client_id);

        echo "<option value=''>Select Project</option>";
        foreach ($projects as $project) {
            echo "<option value='" . htmlspecialchars($project['PROJECT_ID']) . "'>" . 
                 htmlspecialchars($project['PROJECT_NAME']) . 
                 "</option>";
        }
    } else {
        echo "<option value=''>Invalid Client</option>";
    }
}
?>  -->
