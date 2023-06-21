@props(['url' => null, 'workingDirectory' => base_path()])
<div x-data="{
    term: null,
    line: '',
    commands: [],
    commandPointer: 0,
    baseDir: null,
    workingDirectory: @js($workingDirectory),
    get tildeWorkingDir() {
        return this.workingDirectory.replace(baseDir, '~')
    }
}" x-init="baseDir = workingDirectory

term = new Terminal({
    cursorBlink: true,
});
term.open($el);
term.write(`${tildeWorkingDir} => $ `)
term.onKey(({ key, domEvent }) => {
    if (domEvent.keyCode === 38) { // ArrowUp
        if (commandPointer > 0) {
            commandPointer--;
            line = commands[commandPointer];
            term.write(`\x1b[2K\r`); // Clear current line
            term.write(`${tildeWorkingDir} => $ ${line}`);
        }
    }
    if (domEvent.keyCode === 40) { // ArrowDown
        if (commandPointer < commands.length - 1) {
            commandPointer++;
            line = commands[commandPointer];
            term.write(`\x1b[2K\r`); // Clear current line
            term.write(`${tildeWorkingDir} => $ ${line}`);
        } else if (commandPointer === commands.length - 1) {
            commandPointer++;
            line = ''; // Empty line for new command
            term.write(`\x1b[2K\r`); // Clear current line
            term.write(`${tildeWorkingDir} => $ `);
        }
        return;
    }
    if (domEvent.keyCode === 13) { // Enter
        if (line) {
            fetch(@js($url), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.head.querySelector(`meta[name='csrf-token']`).content,
                    },
                    body: JSON.stringify({
                        command: line,
                        working_directory: workingDirectory
                    })
                }).then((res) => res.json())
                .then((data) => {
                    term.writeln(``);
                    if (data.output) {
                        commands = [...commands, line];
                        term.writeln(data.output);
                    }
                    workingDirectory = data.working_directory;
                    term.write(`${tildeWorkingDir} => $ `);
                }).catch((error) => {
                    // Handle error here
                    console.error(error);
                });
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
});

term.attachCustomKeyEventHandler((e) => {
    //  ctrl + c for copy
    if (e.ctrlKey && e.code === `KeyC` && e.type === `keydown`) {
        const selection = term.getSelection();
        if (selection) {
            navigator.clipboard.writeText(selection);
            return false;
        }
    }
    //  ctrl + v for paste
    if (e.ctrlKey && e.code === `KeyV` && e.type === `keydown`) {
        navigator.clipboard.readText().then(text => term.write(text))
        return false;
    };
    return true;
});"></div>
