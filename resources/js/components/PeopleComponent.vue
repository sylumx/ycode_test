<template>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-5xl font-bold mb-20">My Team</h1>
        <div class="grid grid-cols-2 gap-20">
            <div>
                <h2 class="text-xl mb-5">Add new team member</h2>
                <form class="space-y-5" @submit.prevent="submit">
                    <div class="bg-green-200" v-show="success">
                        Team member created successfully.
                    </div>
                    <div>
                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-700"
                            >Name</label
                        >
                        <div class="mt-1">
                            <input
                                type="text"
                                name="name"
                                v-model="name"
                                id="name"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 p-3 border rounded-md"
                                placeholder="Calvin Hawkins"
                            />
                            <div
                                class="text-red-500"
                                v-if="errors && errors.name"
                            >
                                {{ errors.name[0] }}
                            </div>
                        </div>
                    </div>
                    <div>
                        <label
                            for="email"
                            class="block text-sm font-medium text-gray-700"
                            >Email</label
                        >
                        <div class="mt-1">
                            <input
                                type="text"
                                name="email"
                                v-model="email"
                                id="email"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 p-3 border rounded-md"
                                placeholder="you@example.com"
                            />
                            <div
                                class="text-red-500"
                                v-if="errors && errors.email"
                            >
                                {{ errors.email[0] }}
                            </div>
                        </div>
                    </div>
                    <div>
                        <label
                            for="photo"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                        >
                            Photo
                        </label>
                        <div class="mt-1">
                            <div
                                @dragover="dragover"
                                @dragleave="dragleave"
                                @drop="drop"
                                class="w-full flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md upload "
                            >
                                <div class="space-y-1 text-center">
                                    <svg
                                        class="mx-auto h-12 w-12 text-gray-400"
                                        stroke="currentColor"
                                        fill="none"
                                        viewBox="0 0 48 48"
                                        aria-hidden="true"
                                    >
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label
                                            for="file-upload"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500"
                                        >
                                            <span>Upload a file</span>
                                            <input
                                                id="file-upload"
                                                name="file-upload"
                                                type="file"
                                                @change="onChange"
                                                ref="file"
                                                accept=".pdf,.jpg,.jpeg,.png,.gif"
                                                class="sr-only"
                                            />
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, GIF up to 10MB
                                    </p>
                                </div>
                            </div>
                            <div
                                class="text-red-500"
                                v-if="errors && errors.photo"
                            >
                                {{ errors.photo[0] }}
                            </div>
                            <div v-if="this.filelist.length" v-cloak>
                                <div
                                    v-for="file in filelist"
                                    :key="file.key"
                                    class="flex flex-col mt-2"
                                >
                                    <div
                                        class="flex flex-row justify-between justify-items-center align-middle mb-1 p-2 bg-green-100"
                                    >
                                        <span class="tracking-wide">{{
                                            file.name
                                        }}</span>
                                        <a
                                            class="text-white font-bold cursor-pointer button bg-red-600 px-1 rounded hover:bg-red-500"
                                            @click="
                                                remove(filelist.indexOf(file))
                                            "
                                            >X
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="submit"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Submit
                    </button>
                </form>
            </div>
            <div>
                <ul class="divide-y divide-gray-200">
                    <li
                        class="py-4 flex"
                        v-for="person in people"
                        :key="person.id"
                    >
                        <div v-if="person.fields.Photo">
                            <img
                                class="h-10 w-10 rounded-full"
                                :src="person.fields.Photo[0].url"
                                alt=""
                            />
                        </div>
                        <div v-else>
                            <svg
                                class="h-10 w-10 rounded-full"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">
                                {{ person.fields.Name }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ person.fields.Email }}
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [],
    data() {
        return {
            people: [],
            name: "",
            email: "",
            filelist: [],
            success: false,
            errors: {}
        };
    },
    mounted() {
        this.getPeople();
    },
    watch: {},
    methods: {
        getPeople() {
            axios.get("/getPeople").then(res => {
                this.people = res.data.records;
            });
        },
        onChange() {
            this.filelist = [...this.$refs.file.files];
        },
        remove(i) {
            this.filelist.splice(i, 1);
        },
        dragover(event) {
            event.preventDefault();
            // Add some visual fluff to show the user can drop its files
            if (!event.currentTarget.classList.contains("bg-green-300")) {
                event.currentTarget.classList.remove("bg-gray-100");
                event.currentTarget.classList.add("bg-green-300");
            }
        },
        dragleave(event) {
            // Clean up
            event.currentTarget.classList.add("bg-gray-100");
            event.currentTarget.classList.remove("bg-green-300");
        },
        drop(event) {
            event.preventDefault();
            this.$refs.file.files = event.dataTransfer.files;
            this.onChange(); // Trigger the onChange event manually
            // Clean up
            event.currentTarget.classList.add("bg-gray-100");
            event.currentTarget.classList.remove("bg-green-300");
        },
        submit() {
            const fd = new FormData();
            fd.append("name", this.name);
            fd.append("email", this.email);
            if (this.filelist.length > 0) {
                fd.append("photo", this.filelist[0], this.filelist.name);
            }
            axios
                .post("/submit/formdata", fd)
                .then(res => {
                    this.name = "";
                    this.email = "";
                    this.filelist = [];
                    this.success = true;
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }
                });
            // manually refresh data
            this.getPeople();
        }
    }
};
</script>

<style scoped>
[v-cloak] {
    display: none;
}
</style>
