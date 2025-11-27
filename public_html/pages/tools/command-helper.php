<?php
/**
 * Command Helper Tool
 * Generators for Cron, Chmod, Tar, Ls, Chown, User, Netcat
 */
?>
<div class="flex h-[calc(100vh-64px)] bg-gray-50 dark:bg-slate-900">
    <!-- Sidebar -->
    <div class="w-64 bg-white dark:bg-slate-800 border-r border-gray-200 dark:border-slate-700 flex flex-col">
        <div class="p-4 border-b border-gray-200 dark:border-slate-700">
            <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined">terminal</span>
                Command Helper
            </h1>
        </div>
        <nav class="flex-1 overflow-y-auto p-2 space-y-1">
            <button onclick="switchTab('cron')" id="nav-cron" class="nav-btn w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                <span class="material-symbols-outlined">schedule</span>
                Cron Job
            </button>
            <button onclick="switchTab('chmod')" id="nav-chmod" class="nav-btn w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                <span class="material-symbols-outlined">lock</span>
                Chmod (Permissions)
            </button>
            <button onclick="switchTab('chown')" id="nav-chown" class="nav-btn w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                <span class="material-symbols-outlined">person</span>
                Chown (Ownership)
            </button>
            <button onclick="switchTab('tar')" id="nav-tar" class="nav-btn w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                <span class="material-symbols-outlined">folder_zip</span>
                Tar (Archives)
            </button>
            <button onclick="switchTab('ls')" id="nav-ls" class="nav-btn w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                <span class="material-symbols-outlined">list</span>
                Ls (List Files)
            </button>
            <button onclick="switchTab('user')" id="nav-user" class="nav-btn w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                <span class="material-symbols-outlined">group_add</span>
                User & Group
            </button>
            <button onclick="switchTab('netcat')" id="nav-netcat" class="nav-btn w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                <span class="material-symbols-outlined">router</span>
                Netcat (Network)
            </button>
        </nav>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-hidden relative">
        <div class="h-full overflow-auto p-8">
            <div class="max-w-4xl mx-auto space-y-6">
                
                <!-- Cron Job -->
                <div id="view-cron" class="view-section hidden">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Cron Schedule Generator</h2>
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6 shadow-sm">
                        <!-- Presets -->
                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quick Presets</label>
                            <div class="flex flex-wrap gap-2">
                                <button onclick="setCron('* * * * *')" class="px-3 py-1 text-xs rounded-full bg-gray-100 dark:bg-slate-700 hover:bg-primary hover:text-white transition-colors">Every Minute</button>
                                <button onclick="setCron('0 * * * *')" class="px-3 py-1 text-xs rounded-full bg-gray-100 dark:bg-slate-700 hover:bg-primary hover:text-white transition-colors">Hourly</button>
                                <button onclick="setCron('0 0 * * *')" class="px-3 py-1 text-xs rounded-full bg-gray-100 dark:bg-slate-700 hover:bg-primary hover:text-white transition-colors">Daily</button>
                                <button onclick="setCron('0 0 * * 0')" class="px-3 py-1 text-xs rounded-full bg-gray-100 dark:bg-slate-700 hover:bg-primary hover:text-white transition-colors">Weekly</button>
                                <button onclick="setCron('0 0 1 * *')" class="px-3 py-1 text-xs rounded-full bg-gray-100 dark:bg-slate-700 hover:bg-primary hover:text-white transition-colors">Monthly</button>
                            </div>
                        </div>

                        <!-- Fields -->
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase">Minute</label>
                                <input type="text" id="cron-min" value="*" class="cron-field w-full p-2 text-center font-mono rounded border border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:outline-none">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase">Hour</label>
                                <input type="text" id="cron-hour" value="*" class="cron-field w-full p-2 text-center font-mono rounded border border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:outline-none">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase">Day</label>
                                <input type="text" id="cron-dom" value="*" class="cron-field w-full p-2 text-center font-mono rounded border border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:outline-none">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase">Month</label>
                                <input type="text" id="cron-mon" value="*" class="cron-field w-full p-2 text-center font-mono rounded border border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:outline-none">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase">Weekday</label>
                                <input type="text" id="cron-dow" value="*" class="cron-field w-full p-2 text-center font-mono rounded border border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:outline-none">
                            </div>
                        </div>

                        <!-- Result -->
                        <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-6 border border-gray-200 dark:border-slate-700">
                            <div class="mb-4">
                                <label class="block text-xs text-gray-500 mb-1">Command</label>
                                <input type="text" id="cron-cmd" value="/path/to/script.sh" class="w-full p-2 font-mono text-sm rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-primary focus:outline-none">
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex-1 font-mono text-xl text-primary font-bold bg-white dark:bg-slate-800 p-4 rounded border border-gray-200 dark:border-slate-700" id="cron-result"></div>
                                <button onclick="copyToClipboard(document.getElementById('cron-result').textContent.trim(), this)" class="p-4 rounded-lg bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 dark:hover:bg-slate-600 transition-colors">
                                    <span class="material-symbols-outlined">content_copy</span>
                                </button>
                            </div>
                            <div class="mt-4 flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <span class="material-symbols-outlined text-lg">info</span>
                                <span id="cron-human">Every minute</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chmod -->
                <div id="view-chmod" class="view-section hidden">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Chmod Permission Calculator</h2>
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                            <!-- Owner -->
                            <div class="space-y-3">
                                <h3 class="font-bold text-gray-700 dark:text-gray-300 border-b pb-2">Owner</h3>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="chmod-cb w-4 h-4 text-primary rounded" data-val="400"><span>Read (4)</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="chmod-cb w-4 h-4 text-primary rounded" data-val="200"><span>Write (2)</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="chmod-cb w-4 h-4 text-primary rounded" data-val="100"><span>Execute (1)</span></label>
                            </div>
                            <!-- Group -->
                            <div class="space-y-3">
                                <h3 class="font-bold text-gray-700 dark:text-gray-300 border-b pb-2">Group</h3>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="chmod-cb w-4 h-4 text-primary rounded" data-val="40"><span>Read (4)</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="chmod-cb w-4 h-4 text-primary rounded" data-val="20"><span>Write (2)</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="chmod-cb w-4 h-4 text-primary rounded" data-val="10"><span>Execute (1)</span></label>
                            </div>
                            <!-- Public -->
                            <div class="space-y-3">
                                <h3 class="font-bold text-gray-700 dark:text-gray-300 border-b pb-2">Public</h3>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="chmod-cb w-4 h-4 text-primary rounded" data-val="4"><span>Read (4)</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="chmod-cb w-4 h-4 text-primary rounded" data-val="2"><span>Write (2)</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="chmod-cb w-4 h-4 text-primary rounded" data-val="1"><span>Execute (1)</span></label>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-6 border border-gray-200 dark:border-slate-700">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="flex-1"><label class="block text-xs text-gray-500 mb-1">File</label><input type="text" id="chmod-file" value="filename" class="w-full p-2 font-mono text-sm rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800"></div>
                                <div class="w-24"><label class="block text-xs text-gray-500 mb-1">Octal</label><input type="text" id="chmod-octal" readonly class="w-full p-2 font-mono text-sm rounded border border-gray-300 dark:border-slate-600 bg-gray-100 dark:bg-slate-700 text-center font-bold"></div>
                                <div class="w-32"><label class="block text-xs text-gray-500 mb-1">Symbolic</label><input type="text" id="chmod-symbolic" readonly class="w-full p-2 font-mono text-sm rounded border border-gray-300 dark:border-slate-600 bg-gray-100 dark:bg-slate-700 text-center font-bold"></div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex-1 font-mono text-xl text-primary font-bold bg-white dark:bg-slate-800 p-4 rounded border border-gray-200 dark:border-slate-700" id="chmod-result"></div>
                                <button onclick="copyToClipboard(document.getElementById('chmod-result').textContent.trim(), this)" class="p-4 rounded-lg bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 dark:hover:bg-slate-600 transition-colors"><span class="material-symbols-outlined">content_copy</span></button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chown -->
                <div id="view-chown" class="view-section hidden">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Chown Ownership Generator</h2>
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Owner (User)</label>
                                <input type="text" id="chown-user" value="www-data" class="w-full p-2 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Group</label>
                                <input type="text" id="chown-group" value="www-data" class="w-full p-2 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">File / Directory</label>
                                <input type="text" id="chown-target" value="/var/www/html" class="w-full p-2 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center gap-2 cursor-pointer p-2">
                                    <input type="checkbox" id="chown-recursive" class="w-4 h-4 text-primary rounded">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Recursive (-R)</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex-1 font-mono text-xl text-primary font-bold bg-white dark:bg-slate-800 p-4 rounded border border-gray-200 dark:border-slate-700" id="chown-result"></div>
                            <button onclick="copyToClipboard(document.getElementById('chown-result').textContent.trim(), this)" class="p-4 rounded-lg bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 dark:hover:bg-slate-600 transition-colors"><span class="material-symbols-outlined">content_copy</span></button>
                        </div>
                    </div>
                </div>

                <!-- Tar -->
                <div id="view-tar" class="view-section hidden">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Tar Command Generator</h2>
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Action</label>
                                <select id="tar-action" class="w-full p-2 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                                    <option value="create">Create Archive</option>
                                    <option value="extract">Extract Archive</option>
                                    <option value="list">List Contents</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Compression</label>
                                <select id="tar-compression" class="w-full p-2 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                                    <option value="gzip">Gzip (.tar.gz)</option>
                                    <option value="bzip2">Bzip2 (.tar.bz2)</option>
                                    <option value="xz">XZ (.tar.xz)</option>
                                    <option value="none">None (.tar)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Archive Name</label>
                                <input type="text" id="tar-archive" value="archive.tar.gz" class="w-full p-2 font-mono text-sm rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Target</label>
                                <input type="text" id="tar-target" value="/path/to/folder" class="w-full p-2 font-mono text-sm rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex-1 font-mono text-xl text-primary font-bold bg-white dark:bg-slate-800 p-4 rounded border border-gray-200 dark:border-slate-700" id="tar-result"></div>
                            <button onclick="copyToClipboard(document.getElementById('tar-result').textContent.trim(), this)" class="p-4 rounded-lg bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 dark:hover:bg-slate-600 transition-colors"><span class="material-symbols-outlined">content_copy</span></button>
                        </div>
                    </div>
                </div>

                <!-- LS -->
                <div id="view-ls" class="view-section hidden">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Ls Command Generator</h2>
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="space-y-3">
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" id="ls-long" class="ls-opt w-4 h-4 text-primary rounded" checked><span>Long Format (-l)</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" id="ls-all" class="ls-opt w-4 h-4 text-primary rounded"><span>Show Hidden (-a)</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" id="ls-human" class="ls-opt w-4 h-4 text-primary rounded" checked><span>Human Readable Sizes (-h)</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" id="ls-recursive" class="ls-opt w-4 h-4 text-primary rounded"><span>Recursive (-R)</span></label>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort By</label>
                                    <select id="ls-sort" class="w-full p-2 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                                        <option value="">Name (Default)</option>
                                        <option value="t">Modification Time (-t)</option>
                                        <option value="S">Size (-S)</option>
                                        <option value="X">Extension (-X)</option>
                                    </select>
                                </div>
                                <label class="flex items-center gap-2 cursor-pointer mt-2"><input type="checkbox" id="ls-reverse" class="ls-opt w-4 h-4 text-primary rounded"><span>Reverse Order (-r)</span></label>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Target Directory</label>
                                <input type="text" id="ls-target" value="" placeholder="(current directory)" class="w-full p-2 font-mono text-sm rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex-1 font-mono text-xl text-primary font-bold bg-white dark:bg-slate-800 p-4 rounded border border-gray-200 dark:border-slate-700" id="ls-result"></div>
                            <button onclick="copyToClipboard(document.getElementById('ls-result').textContent.trim(), this)" class="p-4 rounded-lg bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 dark:hover:bg-slate-600 transition-colors"><span class="material-symbols-outlined">content_copy</span></button>
                        </div>
                    </div>
                </div>

                <!-- User/Group -->
                <div id="view-user" class="view-section hidden">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">User & Group Management</h2>
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6 shadow-sm">
                        <div class="mb-6">
                            <div class="flex gap-4 border-b border-gray-200 dark:border-slate-700 pb-4">
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="user-action" value="useradd" checked class="text-primary"><span>Add User</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="user-action" value="usermod" class="text-primary"><span>Modify User</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="user-action" value="groupadd" class="text-primary"><span>Add Group</span></label>
                            </div>
                        </div>

                        <div id="user-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Username / Group Name</label>
                                <input type="text" id="user-name" value="jdoe" class="w-full p-2 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                            </div>
                            <div class="user-only">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Primary Group (-g)</label>
                                <input type="text" id="user-group" placeholder="Optional" class="w-full p-2 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                            </div>
                            <div class="user-only">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Secondary Groups (-G)</label>
                                <input type="text" id="user-groups" placeholder="sudo,docker" class="w-full p-2 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                            </div>
                            <div class="user-only">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Shell (-s)</label>
                                <input type="text" id="user-shell" value="/bin/bash" class="w-full p-2 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                            </div>
                            <div class="user-only">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Home Directory (-d)</label>
                                <input type="text" id="user-home" placeholder="/home/jdoe" class="w-full p-2 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                            </div>
                            <div class="user-only flex items-end pb-2">
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" id="user-create-home" checked class="w-4 h-4 text-primary rounded"><span>Create Home Dir (-m)</span></label>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="flex-1 font-mono text-xl text-primary font-bold bg-white dark:bg-slate-800 p-4 rounded border border-gray-200 dark:border-slate-700" id="user-result"></div>
                            <button onclick="copyToClipboard(document.getElementById('user-result').textContent.trim(), this)" class="p-4 rounded-lg bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 dark:hover:bg-slate-600 transition-colors"><span class="material-symbols-outlined">content_copy</span></button>
                        </div>
                    </div>
                </div>

                <!-- Netcat -->
                <div id="view-netcat" class="view-section hidden">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Netcat Command Generator</h2>
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mode</label>
                                <select id="nc-mode" class="w-full p-2 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                                    <option value="connect">Connect to Server</option>
                                    <option value="listen">Listen (Server)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Protocol</label>
                                <select id="nc-proto" class="w-full p-2 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                                    <option value="tcp">TCP (Default)</option>
                                    <option value="udp">UDP (-u)</option>
                                </select>
                            </div>
                            <div id="nc-host-div">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Host / IP</label>
                                <input type="text" id="nc-host" value="localhost" class="w-full p-2 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Port</label>
                                <input type="number" id="nc-port" value="8080" class="w-full p-2 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                            </div>
                            <div class="col-span-2 space-y-3">
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" id="nc-verbose" checked class="w-4 h-4 text-primary rounded"><span>Verbose (-v)</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" id="nc-keep" class="w-4 h-4 text-primary rounded"><span>Keep Open (-k) [Listen only]</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" id="nc-exec" class="w-4 h-4 text-primary rounded"><span>Execute Shell (-e /bin/bash) [Dangerous]</span></label>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex-1 font-mono text-xl text-primary font-bold bg-white dark:bg-slate-800 p-4 rounded border border-gray-200 dark:border-slate-700" id="nc-result"></div>
                            <button onclick="copyToClipboard(document.getElementById('nc-result').textContent.trim(), this)" class="p-4 rounded-lg bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 dark:hover:bg-slate-600 transition-colors"><span class="material-symbols-outlined">content_copy</span></button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/cronstrue@latest/dist/cronstrue.min.js"></script>
<script>
// --- Navigation ---
function switchTab(tab) {
    document.querySelectorAll('.view-section').forEach(el => el.classList.add('hidden'));
    document.getElementById(`view-${tab}`).classList.remove('hidden');
    
    document.querySelectorAll('.nav-btn').forEach(el => {
        el.classList.remove('bg-primary', 'text-white');
        el.classList.add('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-100', 'dark:hover:bg-slate-700');
    });
    
    const btn = document.getElementById(`nav-${tab}`);
    btn.classList.remove('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-100', 'dark:hover:bg-slate-700');
    btn.classList.add('bg-primary', 'text-white');
}

// Initialize
switchTab('cron');

// --- Cron Logic ---
const cronFields = document.querySelectorAll('.cron-field');
const cronCmd = document.getElementById('cron-cmd');
const cronResult = document.getElementById('cron-result');
const cronHuman = document.getElementById('cron-human');

function updateCron() {
    const min = document.getElementById('cron-min').value;
    const hour = document.getElementById('cron-hour').value;
    const dom = document.getElementById('cron-dom').value;
    const mon = document.getElementById('cron-mon').value;
    const dow = document.getElementById('cron-dow').value;
    const cmd = cronCmd.value;
    const expression = `${min} ${hour} ${dom} ${mon} ${dow}`;
    cronResult.textContent = `${expression} ${cmd}`;
    try {
        cronHuman.textContent = cronstrue.toString(expression);
        cronHuman.classList.remove('text-red-500');
    } catch (e) {
        cronHuman.textContent = 'Invalid cron expression';
        cronHuman.classList.add('text-red-500');
    }
}
cronFields.forEach(f => f.addEventListener('input', updateCron));
cronCmd.addEventListener('input', updateCron);
window.setCron = (expr) => {
    const parts = expr.split(' ');
    if (parts.length === 5) {
        document.getElementById('cron-min').value = parts[0];
        document.getElementById('cron-hour').value = parts[1];
        document.getElementById('cron-dom').value = parts[2];
        document.getElementById('cron-mon').value = parts[3];
        document.getElementById('cron-dow').value = parts[4];
        updateCron();
    }
};
updateCron();

// --- Chmod Logic ---
const chmodCbs = document.querySelectorAll('.chmod-cb');
const chmodFile = document.getElementById('chmod-file');
const chmodOctal = document.getElementById('chmod-octal');
const chmodSymbolic = document.getElementById('chmod-symbolic');
const chmodResult = document.getElementById('chmod-result');

function updateChmod() {
    let octal = 0;
    const map = ['---', '--x', '-w-', '-wx', 'r--', 'r-x', 'rw-', 'rwx'];
    chmodCbs.forEach(cb => { if (cb.checked) octal += parseInt(cb.getAttribute('data-val')); });
    const owner = Math.floor(octal / 100);
    const group = Math.floor((octal % 100) / 10);
    const public = octal % 10;
    const symbolic = map[owner] + map[group] + map[public];
    const octalStr = octal.toString().padStart(3, '0');
    chmodOctal.value = octalStr;
    chmodSymbolic.value = symbolic;
    chmodResult.textContent = `chmod ${octalStr} ${chmodFile.value}`;
}
chmodCbs.forEach(cb => cb.addEventListener('change', updateChmod));
chmodFile.addEventListener('input', updateChmod);
updateChmod();

// --- Chown Logic ---
const chownUser = document.getElementById('chown-user');
const chownGroup = document.getElementById('chown-group');
const chownTarget = document.getElementById('chown-target');
const chownRecursive = document.getElementById('chown-recursive');
const chownResult = document.getElementById('chown-result');

function updateChown() {
    let cmd = 'chown';
    if (chownRecursive.checked) cmd += ' -R';
    let ownership = chownUser.value;
    if (chownGroup.value) ownership += `:${chownGroup.value}`;
    chownResult.textContent = `${cmd} ${ownership} ${chownTarget.value}`;
}
[chownUser, chownGroup, chownTarget, chownRecursive].forEach(el => el.addEventListener('input', updateChown));
updateChown();

// --- Tar Logic ---
const tarAction = document.getElementById('tar-action');
const tarCompression = document.getElementById('tar-compression');
const tarArchive = document.getElementById('tar-archive');
const tarTarget = document.getElementById('tar-target');
const tarResult = document.getElementById('tar-result');

function updateTar() {
    const action = tarAction.value;
    const comp = tarCompression.value;
    const archive = tarArchive.value;
    const target = tarTarget.value;
    let flags = '-';
    if (action === 'create') flags += 'c';
    else if (action === 'extract') flags += 'x';
    else if (action === 'list') flags += 't';
    if (comp === 'gzip') flags += 'z';
    else if (comp === 'bzip2') flags += 'j';
    else if (comp === 'xz') flags += 'J';
    flags += 'vf';
    if (action === 'create') tarResult.textContent = `tar ${flags} ${archive} ${target}`;
    else {
        tarResult.textContent = `tar ${flags} ${archive}`;
        if (target && action === 'extract') tarResult.textContent += ` -C ${target}`;
    }
    if (action === 'create') {
        let ext = '.tar';
        if (comp === 'gzip') ext += '.gz';
        else if (comp === 'bzip2') ext += '.bz2';
        else if (comp === 'xz') ext += '.xz';
        if (!archive.endsWith(ext)) {
            const base = archive.split('.')[0];
            tarArchive.value = base + ext;
        }
    }
}
[tarAction, tarCompression, tarArchive, tarTarget].forEach(el => el.addEventListener('input', updateTar));
updateTar();

// --- LS Logic ---
const lsLong = document.getElementById('ls-long');
const lsAll = document.getElementById('ls-all');
const lsHuman = document.getElementById('ls-human');
const lsRecursive = document.getElementById('ls-recursive');
const lsSort = document.getElementById('ls-sort');
const lsReverse = document.getElementById('ls-reverse');
const lsTarget = document.getElementById('ls-target');
const lsResult = document.getElementById('ls-result');

function updateLs() {
    let flags = '';
    if (lsLong.checked) flags += 'l';
    if (lsAll.checked) flags += 'a';
    if (lsHuman.checked) flags += 'h';
    if (lsRecursive.checked) flags += 'R';
    if (lsSort.value) flags += lsSort.value;
    if (lsReverse.checked) flags += 'r';
    
    let cmd = 'ls';
    if (flags) cmd += ` -${flags}`;
    if (lsTarget.value) cmd += ` ${lsTarget.value}`;
    
    lsResult.textContent = cmd;
}
document.querySelectorAll('.ls-opt, #ls-sort, #ls-target').forEach(el => el.addEventListener('input', updateLs));
updateLs();

// --- User/Group Logic ---
const userAction = document.getElementsByName('user-action');
const userName = document.getElementById('user-name');
const userGroup = document.getElementById('user-group');
const userGroups = document.getElementById('user-groups');
const userShell = document.getElementById('user-shell');
const userHome = document.getElementById('user-home');
const userCreateHome = document.getElementById('user-create-home');
const userResult = document.getElementById('user-result');

function updateUser() {
    let action = '';
    for(const r of userAction) if(r.checked) action = r.value;
    
    // Toggle fields visibility
    const isGroup = action === 'groupadd';
    document.querySelectorAll('.user-only').forEach(el => el.classList.toggle('hidden', isGroup));
    
    let cmd = action;
    
    if (isGroup) {
        cmd += ` ${userName.value}`;
    } else {
        if (userCreateHome.checked && action === 'useradd') cmd += ' -m';
        if (userShell.value) cmd += ` -s ${userShell.value}`;
        if (userGroup.value) cmd += ` -g ${userGroup.value}`;
        if (userGroups.value) cmd += ` -G ${userGroups.value}`;
        if (userHome.value) cmd += ` -d ${userHome.value}`;
        cmd += ` ${userName.value}`;
    }
    
    userResult.textContent = cmd;
}
document.querySelectorAll('input[name="user-action"], #user-name, #user-group, #user-groups, #user-shell, #user-home, #user-create-home').forEach(el => el.addEventListener('input', updateUser));
updateUser();

// --- Netcat Logic ---
const ncMode = document.getElementById('nc-mode');
const ncProto = document.getElementById('nc-proto');
const ncHost = document.getElementById('nc-host');
const ncPort = document.getElementById('nc-port');
const ncVerbose = document.getElementById('nc-verbose');
const ncKeep = document.getElementById('nc-keep');
const ncExec = document.getElementById('nc-exec');
const ncResult = document.getElementById('nc-result');

function updateNetcat() {
    const isListen = ncMode.value === 'listen';
    document.getElementById('nc-host-div').classList.toggle('hidden', isListen);
    
    let cmd = 'nc';
    if (isListen) cmd += ' -l';
    if (ncProto.value === 'udp') cmd += ' -u';
    if (ncVerbose.checked) cmd += ' -v';
    if (isListen && ncKeep.checked) cmd += ' -k';
    if (ncExec.checked) cmd += ' -e /bin/bash';
    
    cmd += ` -p ${ncPort.value}`;
    if (!isListen) cmd += ` ${ncHost.value}`;
    
    ncResult.textContent = cmd;
}
[ncMode, ncProto, ncHost, ncPort, ncVerbose, ncKeep, ncExec].forEach(el => el.addEventListener('input', updateNetcat));
updateNetcat();

// Utilities
window.copyToClipboard = async (text, btn) => {
    try {
        await navigator.clipboard.writeText(text);
        const icon = btn.querySelector('.material-symbols-outlined');
        const originalIcon = icon.textContent;
        icon.textContent = 'check';
        btn.classList.add('text-green-500');
        setTimeout(() => {
            icon.textContent = originalIcon;
            btn.classList.remove('text-green-500');
        }, 1500);
    } catch (err) {
        console.error('Failed to copy:', err);
    }
};
</script>
