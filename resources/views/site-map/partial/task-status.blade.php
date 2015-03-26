@if ($task->isCompleted())
    <div class="alert alert-success">
        <strong>Completed!</strong>
        <a href="/{{ $task->filename }}">Here is your link!</a>
    </div>
@elseif($task->isNew())
    <div class="alert alert-success">
        <strong>Success!</strong>
        Your request has been queued. Please wait for tour sitemap (generally it takes not more than 5min).
    </div>
@elseif($task->isProcessing())
    <div class="alert alert-warning">
        <strong>Success!</strong>
        Your request is being processed. Please wait for tour sitemap (generally it takes not more than 5min).
    </div>
@elseif($task->isFailed())
    <div class="alert alert-danger">
        <strong>Error!</strong>
        Your request failed.<br><br>
        <ul>
            <li>{{ $task->errorReason }}</li>
        </ul>
    </div>
@endif

