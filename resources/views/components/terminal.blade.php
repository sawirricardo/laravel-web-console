@props(['url' => null, 'workingDirectory' => base_path()])
<div x-data="{
    term: null,
    line: '',
    commands: [],
    commandPointer: 0,
    isSelectingCommand: false,
    baseDir: null,
    workingDirectory: @js($workingDirectory),
    setCurrentLine(line = '') {
        this.line = line;
        this.term.write(`\x1b[2K\r`)
        const tildeWorkingDir = this.workingDirectory.replace(this.baseDir, '~');
        this.term.write(`${tildeWorkingDir} => $ ${line}`)
    },
    handleBackspace() {
        if (this.line) {
            this.line = this.line.slice(0, this.line.length - 1)
            this.term.write(`\b \b`);
        }
    },
    replaceToCrlf(text) {
        if (/\r\n/.test(text)) return text;
        return text.replace(/\n/g, '\r\n');
    }
}" x-init="baseDir = workingDirectory

term = new Terminal({
    cursorBlink: true,
});
term.open($el);
setCurrentLine();
term.onKey(({ key, domEvent }) => {
    if (domEvent.keyCode === 38) { // ArrowUp
        if (commands.length == 0) return;
        if (isSelectingCommand) {
            if (commandPointer < commands.length - 1) {
                commandPointer++
            }
        }
        if (!isSelectingCommand) {
            isSelectingCommand = true;
        }
        setCurrentLine(commands[commandPointer])
        return;
    }
    if (domEvent.keyCode === 40) { // ArrowDown
        if (isSelectingCommand && commandPointer == 0) {
            isSelectingCommand = false;
            setCurrentLine();
        }
        if (commandPointer > 0) {
            commandPointer--;
            setCurrentLine(commands[commandPointer]);
        }
        return;
    }
    if (domEvent.keyCode === 13) { // Enter
        if (!line) return;
        commands = [line, ...commands];
        term.writeln(``);
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
            }).then((res) => {
                if (res.ok) return res.json()
                return Promise.reject(res);
            })
            .then((data) => {
                if (data.output) {
                    term.writeln(replaceToCrlf(data.output));
                }
                workingDirectory = data.working_directory;
                setCurrentLine();
            }).catch((e) => {
                console.error(e);
            });
        return;
    }
    if (domEvent.keyCode === 8) { //BackSpace
        handleBackspace()
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
        navigator.clipboard.readText().then(text => {
            term.write(replaceToCrlf(line))
        })
        return false;
    };
    return true;
});"></div>
