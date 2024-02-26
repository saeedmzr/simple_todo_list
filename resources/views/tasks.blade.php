<table>
    <tr>
        <th>Id</th>
        <th>Title</th>
        <th>Description</th>
        <th>Status</th>
    </tr>
    @foreach($tasks as $task)
        <tr data-task-id="{{$task->id}}">
            <td>{{$task->id}}</td>
            <td>{{$task->title}}</td>
            <td>{{$task->description}}</td>
            <td>{{$task->status}}</td>
        </tr>

    @endforeach
</table>
<style>
    table {
        border-collapse: collapse;
        width: 100%; /* Adjust width as needed */
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
    }

    th {
        text-align: left;
        background-color: #f2f2f2; /* Light gray background for headers */
    }
</style>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

<script>
    // Replace these with your actual credentials
    const pusher_app_id = "{{$pusherData["pusher_app_id"]}}";
    const pusher_key = "{{$pusherData["pusher_key"]}}";
    const pusher_secret = "{{$pusherData["pusher_secret"]}}";
    const pusher_cluster = "{{$pusherData["pusher_cluster"]}}";

    Pusher.logToConsole = true;

    var pusher = new Pusher(pusher_key, {
        cluster: pusher_cluster
    });


    // Subscribe to the private channel for the current task
    const channel = pusher.subscribe('tasks.{{ $task->id }}');

    // Listen for the "update-task" event on the channel
    channel.bind('tasks', function (data) {
        console.log(data);
        // Update the table with the received data

        // Update the specific row based on the received task data
        const rowToUpdate = document.querySelector(`tr[data-task-id="${data.task.id}"]`);

        if (rowToUpdate) {
            rowToUpdate.children[0].textContent = data.task.title; // Update title
            rowToUpdate.children[1].textContent = data.task.description; // Update description
            rowToUpdate.children[2].textContent = data.task.status; // Update status
        } else {
            console.warn("Unable to find row for task with ID", data.task.id);
        }
    });
</script>
