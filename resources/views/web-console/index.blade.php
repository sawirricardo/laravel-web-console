<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">


<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ __('Web Console') }}</title>
    <link rel="stylesheet" href='https://cdn.jsdelivr.net/npm/xterm@5.2.1/css/xterm.min.css' />
</head>

<body>
    <div x-data="{
        term: null,
        line: '',
        commands: [],
        baseDir: '{{ base_path() }}',
        workingDirectory: '{{ base_path() }}',
    }" x-init="term = new Terminal({
        cursorBlink: true,
    });
    term.open($el);
    term.write(`${workingDirectory} => $ `)
    term.onKey(({ key, domEvent }) => {
        if (domEvent.keyCode === 38) { // ArrowUp
            if (commands.length > 0) {
                // TODO: Should get commands from history
            }
        }
        if (domEvent.keyCode === 40) { // ArrowDown
            if (commands.length > 0) {
                // TODO: Should get latest commands from history, or nothing if reach latest
            }
        }
        if (domEvent.keyCode === 13) { // Enter
            if (line) {
                axios.post(`{{ $interactUrl ?? '' }}`, {
                    command: line,
                    working_directory: workingDirectory,
                }).then((res) => {
                    term.writeln(``);
                    if (res.data.output) {
                        commands = [...commands, line];
                        term.writeln(res.data.output);
                    }
                    workingDirectory = res.data.working_directory
                    term.write(`${workingDirectory} => $ `)
                })
            }
            line = ''
            return;
        }
        if (domEvent.keyCode === 8) { //BackSpace
            if (line) {
                line = line.slice(0, line.length - 1);
                term.write(`\b \b`);
            }
            return;
        }
        line += key;
        term.write(key)
    });"></div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src='https://cdn.jsdelivr.net/npm/xterm@5.2.1/lib/xterm.min.js' defer></script>
</body>

</html>
