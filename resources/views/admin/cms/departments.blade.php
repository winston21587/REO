<x-admin_layout title="Departments & Programs">
    <div class="max-w-7xl mx-auto py-8" x-data="{ 
        showCollegeModal: false, 
        showDeptModal: false, 
        showProgModal: false, 
        editCollege: {}, 
        editDept: {},
        editProg: {},
        selectedCollegeId: null
    }">
        
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2 animate-[fadeIn_0.5s]">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 animate-[fadeIn_0.5s]">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex justify-between items-center mb-8">
             <div>
                <h2 class="text-2xl font-black text-slate-800 font-heading">Academic Structure</h2>
                <p class="text-sm text-slate-500 mt-1">Manage Colleges, Departments, and Programs hierarchy.</p>
             </div>
             <button @click="showCollegeModal = true; editCollege = {}" class="bg-[#8B0000] text-white px-6 py-2.5 rounded-xl font-bold hover:bg-red-800 transition-colors shadow-lg shadow-red-900/20 flex items-center gap-2 transform hover:-translate-y-0.5" title="Add New College">
                <i class="fas fa-plus-circle"></i> Add College
             </button>
        </div>

        <div class="space-y-8">
            @forelse($colleges as $college)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden group/college">
                <!-- College Header -->
                <div class="p-6 bg-slate-50 border-b border-slate-100 flex justify-between items-start">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#8B0000] to-red-600 text-white flex items-center justify-center shadow-md">
                            <i class="fas fa-university text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-xl flex items-center gap-3">
                                {{ $college->name }}
                                <span class="text-xs font-bold text-slate-500 bg-white border border-slate-200 px-2 py-0.5 rounded-md uppercase tracking-wider shadow-sm">{{ $college->code }}</span>
                            </h3>
                            <p class="text-xs text-slate-500 mt-1 font-medium">{{ $college->departments->count() }} Departments • {{ $college->departments->sum(function($dept){ return $dept->programs->count(); }) }} Programs</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                         <button @click="showCollegeModal = true; editCollege = {{ $college }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-blue-600 hover:text-blue-700 hover:shadow-sm transition-all" title="Edit College">
                            <i class="fas fa-edit text-xs"></i>
                         </button>
                         <form action="{{ route('admin.cms.colleges.destroy', $college->id) }}" method="POST" onsubmit="return confirm('Delete this college? All departments and programs under it will be deleted.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-red-600 hover:text-red-700 hover:shadow-sm transition-all" title="Delete College">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Departments List -->
                <div class="p-6 bg-white">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <i class="fas fa-building"></i> Departments
                        </h4>
                        <button @click="showDeptModal = true; editDept = {}; selectedCollegeId = {{ $college->id }}" class="text-xs font-bold text-[#8B0000] hover:text-red-700 flex items-center gap-1 py-1 px-2 rounded hover:bg-red-50 transition-colors">
                            <i class="fas fa-plus"></i> Add Department
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($college->departments as $dept)
                            <div class="border border-slate-100 rounded-xl p-4 hover:border-slate-300 transition-colors group/dept bg-slate-50/50">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h5 class="font-bold text-slate-800 text-sm">{{ $dept->name }}</h5>
                                        <span class="text-[10px] font-bold text-slate-500 bg-slate-200 px-1.5 py-0.5 rounded uppercase">{{ $dept->code }}</span>
                                    </div>
                                    <div class="flex gap-1 opacity-0 group-hover/dept:opacity-100 transition-opacity">
                                        <button @click="showDeptModal = true; editDept = {{ $dept }}; selectedCollegeId = {{ $college->id }}" class="p-1 text-blue-500 hover:text-blue-700" title="Edit Department"><i class="fas fa-pencil-alt text-xs"></i></button>
                                        <form action="{{ route('admin.cms.departments.destroy', $dept->id) }}" method="POST" onsubmit="return confirm('Delete this department?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1 text-red-500 hover:text-red-700" title="Delete Department"><i class="fas fa-trash text-xs"></i></button>
                                        </form>
                                    </div>
                                </div>
                                
                                <!-- Programs -->
                                <div class="pl-3 border-l-2 border-slate-200 space-y-2">
                                    @forelse($dept->programs as $program)
                                        <div class="flex justify-between items-center group/prog">
                                            <span class="text-xs text-slate-600 font-medium truncate pr-2" title="{{ $program->name }}">{{ $program->name }}</span>
                                            <div class="flex gap-1 opacity-0 group-hover/prog:opacity-100 transition-opacity flex-shrink-0">
                                                <button @click="showProgModal = true; editProg = {{ $program }}" class="text-[10px] text-blue-500 hover:text-blue-700"><i class="fas fa-pencil-alt"></i></button>
                                                <form action="{{ route('admin.cms.programs.destroy', $program->id) }}" method="POST" onsubmit="return confirm('Delete program?')" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-[10px] text-red-500 hover:text-red-700"><i class="fas fa-times"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-[10px] text-slate-400 italic">No programs</p>
                                    @endforelse
                                    
                                    <form action="{{ route('admin.cms.programs.store') }}" method="POST" class="mt-2 flex gap-1">
                                        @csrf
                                        <input type="hidden" name="department_id" value="{{ $dept->id }}">
                                        <input type="text" name="name" placeholder="New Program..." required class="flex-1 px-2 py-1 text-[10px] border border-slate-200 rounded focus:ring-1 focus:ring-[#8B0000] outline-none">
                                        <button type="submit" class="bg-slate-800 text-white px-2 py-1 rounded text-[10px] font-bold hover:bg-slate-700"><i class="fas fa-plus"></i></button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 text-center py-8 border-2 border-dashed border-slate-200 rounded-xl">
                                <p class="text-sm text-slate-400 font-medium">No departments in this college yet.</p>
                                <button @click="showDeptModal = true; editDept = {}; selectedCollegeId = {{ $college->id }}" class="text-xs text-[#8B0000] font-bold mt-2 hover:underline">Add First Department</button>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            @empty
                <div class="text-center py-12 bg-white rounded-2xl border-2 border-dashed border-slate-200">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <i class="fas fa-university text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">No Colleges Set Up</h3>
                    <p class="text-sm text-slate-500 mb-4">Start by adding your first college to structure departments and programs.</p>
                    <button @click="showCollegeModal = true; editCollege = {}" class="bg-[#8B0000] text-white px-6 py-2 rounded-lg font-bold hover:bg-red-800 transition-colors">
                        Add College
                    </button>
                </div>
            @endforelse
        </div>

        <!-- College Modal -->
        <div x-show="showCollegeModal" class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display: none;" x-cloak>
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showCollegeModal = false"></div>
            <div class="bg-white rounded-2xl w-full max-w-lg relative z-10 p-6 shadow-xl animate-[fadeInUp_0.3s]">
                <h3 class="text-xl font-bold text-slate-900 mb-6" x-text="editCollege.id ? 'Edit College' : 'New College'"></h3>
                
                <form :action="editCollege.id ? '{{ route('admin.cms.colleges.update', 0) }}'.replace('/0', '/' + editCollege.id) : '{{ route('admin.cms.colleges.store') }}'" method="POST">
                    @csrf
                    <template x-if="editCollege.id">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    
                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">College Name</label>
                            <input type="text" name="name" x-model="editCollege.name" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none" placeholder="e.g. College of Computing Studies">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Code</label>
                            <input type="text" name="code" x-model="editCollege.code" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none" placeholder="e.g. CCS">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Color (Optional)</label>
                            <input type="text" name="color_assign" x-model="editCollege.color_assign" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none" placeholder="e.g. #8B0000">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="showCollegeModal = false" class="px-4 py-2 text-slate-600 font-bold hover:bg-slate-100 rounded-lg transition-colors">Cancel</button>
                        <button type="submit" class="bg-[#8B0000] text-white px-6 py-2 rounded-lg font-bold hover:bg-red-800 transition-colors">Save Details</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Dept Modal -->
        <div x-show="showDeptModal" class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display: none;" x-cloak>
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showDeptModal = false"></div>
            <div class="bg-white rounded-2xl w-full max-w-lg relative z-10 p-6 shadow-xl animate-[fadeInUp_0.3s]">
                <h3 class="text-xl font-bold text-slate-900 mb-6" x-text="editDept.id ? 'Edit Department' : 'New Department'"></h3>
                
                <form :action="editDept.id ? '{{ route('admin.cms.departments.update', 0) }}'.replace('/0', '/' + editDept.id) : '{{ route('admin.cms.departments.store') }}'" method="POST">
                    @csrf
                    <template x-if="editDept.id">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <input type="hidden" name="college_id" :value="selectedCollegeId || editDept.college_id">
                    
                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Department Name</label>
                            <input type="text" name="name" x-model="editDept.name" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Code</label>
                            <input type="text" name="code" x-model="editDept.code" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="showDeptModal = false" class="px-4 py-2 text-slate-600 font-bold hover:bg-slate-100 rounded-lg transition-colors">Cancel</button>
                        <button type="submit" class="bg-[#8B0000] text-white px-6 py-2 rounded-lg font-bold hover:bg-red-800 transition-colors">Save Details</button>
                    </div>
                </form>
            </div>
        </div>
        
         <!-- Program Modal (Edit Only) -->
         <div x-show="showProgModal" class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display: none;" x-cloak>
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showProgModal = false"></div>
            <div class="bg-white rounded-2xl w-full max-w-md relative z-10 p-6 shadow-xl animate-[fadeInUp_0.3s]">
                <h3 class="text-xl font-bold text-slate-900 mb-4">Edit Program</h3>
                
                <form :action="'{{ route('admin.cms.programs.update', 0) }}'.replace('/0', '/' + editProg.id)" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Program Name</label>
                            <input type="text" name="name" x-model="editProg.name" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Code (Optional)</label>
                            <input type="text" name="code" x-model="editProg.code" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="showProgModal = false" class="px-4 py-2 text-slate-600 font-bold hover:bg-slate-100 rounded-lg transition-colors">Cancel</button>
                        <button type="submit" class="bg-[#8B0000] text-white px-6 py-2 rounded-lg font-bold hover:bg-red-800 transition-colors">Update Program</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-admin_layout>
