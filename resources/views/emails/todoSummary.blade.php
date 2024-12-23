<h1>Summary of Last 3 Todos Created</h1>
<ul>
    @foreach ($todos as $todo)
    <li>
        <strong>{{ $todo->title }}</strong>
        : {{ $todo->description }}
        - {{ $todo->isCompleted ? 'Completed' : 'Pending' }}
    </li>
    @endforeach
</ul>