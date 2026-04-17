@extends('layouts.admin')

@section('title','Tasks')

@section('content')
<div class="space-y-6" x-data="taskManager()" x-init="fetchTasks()">
<style>[x-cloak]{display:none}</style>

{{-- ================= HEADER ================= --}}
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold">Tasks</h1>
        <p class="text-sm text-gray-500">Manage follow-ups and action items</p>
    </div>
    <button @click="openModal"
            class="w-full sm:w-auto bg-indigo-600 text-white px-4 py-2 rounded-lg">
        + New Task
    </button>
</div>

{{-- ================= FILTERS ================= --}}
<div class="bg-white p-4 rounded-xl shadow flex flex-col sm:flex-row flex-wrap gap-3">
    <input x-model="search"
           placeholder="Search tasks..."
           class="border rounded px-3 py-2 w-full sm:w-64">

    <select x-model="priority" class="border rounded px-3 py-2 w-full sm:w-auto">
        <option value="All">All Priorities</option>
        <option>High</option>
        <option>Medium</option>
        <option>Low</option>
    </select>

    <select x-model="status" class="border rounded px-3 py-2 w-full sm:w-auto">
        <option value="All">All Status</option>
        <option>Pending</option>
        <option>Completed</option>
    </select>
</div>

{{-- ================= TASK LIST ================= --}}
<div class="space-y-4">
    <template x-for="task in filteredTasks()" :key="task._id">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex flex-col sm:flex-row sm:justify-between gap-3">

                <div>
                    <span class="text-xs px-2 py-1 rounded-full font-semibold"
                          :class="priorityClass(task.priority)">
                        <span x-text="task.priority"></span>
                    </span>

                    <h3 class="font-semibold mt-2"
                        :class="task.status==='Completed' ? 'line-through text-gray-400' : ''"
                        x-text="task.title"></h3>
                </div>

                <div class="flex items-center">
                    <template x-if="task.status==='Pending'">
                        <button @click="markComplete(task._id)"
                                class="text-sm text-indigo-600">
                            Mark Complete
                        </button>
                    </template>

                    <template x-if="task.status==='Completed'">
                        <span class="text-green-600 text-sm font-semibold">
                            Completed
                        </span>
                    </template>
                </div>
            </div>

            <p class="text-sm text-gray-600 mt-2" x-text="task.description"></p>

            <div class="text-xs text-gray-500 mt-3 flex flex-wrap gap-x-2">
                <span>
                    Lead:
                    <span class="text-indigo-600 font-medium"
                          x-text="task.lead_name"></span>
                </span>
                <span>· Assigned:</span>
                <span x-text="task.assigned_to"></span>
            </div>
        </div>
    </template>

    <div x-show="filteredTasks().length===0"
         class="text-center text-gray-500 py-10 bg-white rounded-xl">
        No tasks found
    </div>
</div>

{{-- ================= MODAL ================= --}}
<div x-cloak x-show="showModal"
     class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-lg rounded-xl p-6"
         @click.outside="closeModal">

        <h3 class="text-lg font-bold mb-4">Create Task</h3>

        <form @submit.prevent="saveTask" class="space-y-3">

<select x-model="form.task_type"
        @change="applyTaskTemplate"
        class="border rounded px-3 py-2 w-full">
    <option value="">Select Task Type</option>
    <option value="DOC_FOLLOW">Follow up for Documents</option>
    <option value="SITE_VERIFY">Site / Property Verification</option>
    <option value="CIBIL_CHECK">CIBIL Verification</option>
    <option value="BANK_LOGIN">Bank Login</option>
    <option value="OFFER_SHARE">Loan Offer Sharing</option>
    <option value="AGREEMENT">Agreement Signing</option>
    <option value="DISBURSE">Disbursement Follow-up</option>
    <option value="CUSTOMER_CALL">Customer Follow-up Call</option>
</select>

<textarea x-model="form.description"
          placeholder="Task description"
          class="border rounded px-3 py-2 w-full"></textarea>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
    <select x-model="form.priority" class="border rounded px-3 py-2">
        <option>High</option>
        <option>Medium</option>
        <option>Low</option>
    </select>

    <input type="date"
           x-model="form.due_date"
           class="border rounded px-3 py-2">
</div>

<div class="relative">
<select x-model="form.lead_id"
        @change="onLeadSelect"
        class="border rounded px-3 py-2 w-full">
    <option value="">Select Lead</option>
    <template x-for="lead in leads" :key="lead.id">
        <option :value="lead.id"
                x-text="lead.name + ' - ' + lead.mobile"></option>
    </template>
</select>
</div>

<input x-model="form.assigned_to"
       placeholder="Assigned to"
       class="border rounded px-3 py-2 w-full">

<div class="flex flex-col sm:flex-row justify-end gap-2 pt-2">
    <button type="button"
            @click="closeModal"
            class="px-4 py-2 bg-gray-200 rounded">
        Cancel
    </button>
    <button class="px-4 py-2 bg-indigo-600 text-white rounded">
        Save
    </button>
</div>

        </form>
    </div>
</div>

</div>

{{-- ================= ALPINE ================= --}}
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
function taskManager(){
return {

    tasks: [],
    leads: [],
    selectedLead: {},

    search: '',
    priority: 'All',
    status: 'All',
    showModal: false,

    taskTemplates: {
        DOC_FOLLOW:{title:'Follow up for Documents',desc:'Collect pending documents from customer and verify completeness.'},
        SITE_VERIFY:{title:'Site / Property Verification',desc:'Coordinate with valuation team for site visit and verification.'},
        CIBIL_CHECK:{title:'CIBIL Verification',desc:'Check customer CIBIL score and eligibility.'},
        BANK_LOGIN:{title:'Bank Login',desc:'Login loan case with bank and upload required documents.'},
        OFFER_SHARE:{title:'Loan Offer Sharing',desc:'Share loan offer with customer and explain terms.'},
        AGREEMENT:{title:'Agreement Signing',desc:'Schedule and complete agreement signing with customer.'},
        DISBURSE:{title:'Disbursement Follow-up',desc:'Follow up with bank for loan disbursement.'},
        CUSTOMER_CALL:{title:'Customer Follow-up Call',desc:'Call customer for update and next steps.'}
    },

    form:{
        task_type:'',
        title:'',
        description:'',
        priority:'Medium',
        due_date:'',
        lead_id:'',
        lead_name:'',
        assigned_to:''
    },

    async fetchTasks(){
        this.tasks = await fetch('/tasks/list').then(r=>r.json())
    },

    async fetchLeads(){
        this.leads = await fetch('/leads/list').then(r=>r.json())
    },

    filteredTasks(){
        return this.tasks.filter(t=>{
            let q=this.search.toLowerCase()
            return(
                (this.priority==='All'||t.priority===this.priority) &&
                (this.status==='All'||t.status===this.status) &&
                (t.title.toLowerCase().includes(q))
            )
        })
    },

    applyTaskTemplate(){
        if(!this.form.task_type) return
        const t=this.taskTemplates[this.form.task_type]
        this.form.title=t.title
        this.form.description=t.desc
    },

    openModal(){
        this.showModal=true
        this.fetchLeads()
    },

    closeModal(){
        this.showModal=false
        this.selectedLead={}
        this.form={
            task_type:'',
            title:'',
            description:'',
            priority:'Medium',
            due_date:'',
            lead_id:'',
            lead_name:'',
            assigned_to:''
        }
    },

    async saveTask(){
        await fetch('/tasks',{
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            },
            body:JSON.stringify(this.form)
        })
        this.closeModal()
        this.fetchTasks()
    },

    async markComplete(id){
        await fetch(`/tasks/${id}/complete`,{
            method:'POST',
            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
        })
        this.fetchTasks()
    },

    priorityClass(p){
        return{
            High:'bg-red-100 text-red-800',
            Medium:'bg-yellow-100 text-yellow-800',
            Low:'bg-blue-100 text-blue-800'
        }[p]
    }
}
}
</script>

@endsection
