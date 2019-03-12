<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body style="font-family: Nunito;">
        <div id="log-reader" class="flex mt-10 justify-center">
            <div class="container mx-auto flex flex-col justify-center">
                <div class="flex justify-end mb-10">
                    {{-- <h1 class="text-lg font-semibold text-red-light">Laravel Logger Reader</h1> --}}
                    <div class="w-2/3 flex items-center justify-end">
                        <div class="mr-6">
                            <div class="relative">
                                <div class="pointer-events-none absolute pin-y pin-l flex items-center pr-4 text-grey">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title>synchronize-arrow-clock</title><path d="M12.965,6a1,1,0,0,0-1,1v5.5a1,1,0,0,0,1,1h5a1,1,0,0,0,0-2h-3.75a.25.25,0,0,1-.25-.25V7A1,1,0,0,0,12.965,6Z"/><path d="M12.567,1.258A10.822,10.822,0,0,0,2.818,8.4a.25.25,0,0,1-.271.163L.858,8.309a.514.514,0,0,0-.485.213.5.5,0,0,0-.021.53l2.679,4.7a.5.5,0,0,0,.786.107l3.77-3.746a.5.5,0,0,0-.279-.85L5.593,9.007a.25.25,0,0,1-.192-.35,8.259,8.259,0,1,1,7.866,11.59,1.25,1.25,0,0,0,.045,2.5h.047a10.751,10.751,0,1,0-.792-21.487Z"/></svg>
                                </div>
                                <select v-model="time" class="block appearance-none ml-2 w-full rounded-none text-sm border-b border-grey-darker text-grey-darker bg-white py-1 px-4 pr-8 leading-tight focus:outline-none" id="grid-state">
                                    <option value="today">Today</option>
                                    <option value="yesterday">Yesterday</option>
                                    <option value="all">All</option>
                                </select>
                                <div class="pointer-events-none absolute pin-y pin-r flex items-center text-grey">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="mr-6">
                            <div class="relative">
                                <div class="pointer-events-none absolute pin-y pin-l flex items-center text-grey">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title>filter-1</title><path d="M22.826,3.58A2.251,2.251,0,0,0,21.01,0H2.992A2.25,2.25,0,0,0,1.164,3.563l7.1,10.171v8.015A2.259,2.259,0,0,0,10.716,24a2.9,2.9,0,0,0,1.573-.408c2.733-1.7,2.445-1.524,2.471-1.543a2.261,2.261,0,0,0,.9-1.8V13.737Zm-9.437,9.04a1.25,1.25,0,0,0-.229.721v6.481a.5.5,0,0,1-.244.43c-.328.194-.923.547-1.405.827a.5.5,0,0,1-.751-.432V13.341a1.242,1.242,0,0,0-.225-.715L4.018,3.287a.5.5,0,0,1,.41-.786H19.563a.5.5,0,0,1,.409.788Z"/></svg>
                                </div>
                                <select v-model="type" class="block ml-2 appearance-none w-full rounded-none text-sm border-b border-grey-darker text-grey-darker bg-white py-1 px-4 pr-8 leading-tight focus:outline-none" id="grid-state">
                                    <option value="all">All</option>
                                    <option value="error">Error</option>
                                    <option value="debug">Debug</option>
                                    <option value="alert">Alert</option>
                                    <option value="critical">Critical</option>
                                    <option value="warning">Warning</option>
                                </select>
                                <div class="pointer-events-none absolute pin-y pin-r flex items-center text-grey">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="mb-20 w-full flex flex-col w-full text-left table-collapse bg-white rounded-b-lg shadow">
                    <thead class="flex w-full">
                        <tr class="flex w-full">
                            <th class="flex-1 text-sm font-medium text-grey-lighter p-3 bg-grey-darker rounded-tl-lg">Date</th>
                            {{-- <th class="text-sm font-medium text-grey-lighter p-3 bg-grey-darker">ENV</th> --}}
                            <th class="w-1/6 text-sm font-medium text-grey-lighter p-3 bg-grey-darker">Type</th>
                            <th class="w-3/5 lex text-sm font-medium text-grey-lighter p-3 bg-grey-darker rounded-tr-lg">Message</th>
                        </tr>
                    </thead>
                    <tbody class="align-baseline">
                        <tr v-if="!loading" v-for="logger in loggers" class="flex w-full">
                            <td class="flex-1 p-4 border-t border-grey-lighter text-sm text-grey-dark whitespace-no-wrap break-words">@{{ new Date(logger['date']).toUTCString() }}</td>
                            <td class="w-1/6 p-4 border-t border-grey-lighter text-sm text-grey-darker whitespace-no-wrap">@{{ logger['type'] }}</td>
                            <td class="w-3/5 flex p-4 border-t border-grey-lighter text-sm text-red-darker break-words leading-normal">
                                <div class="w-full overflow-wrap">
                                    @{{ logger['message'] }}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="loading" class="mt-10 w-full flex items-center justify-center">
                <svg width="100px"  height="100px"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-ball" style="background: none;"><circle cx="50" cy="44.5619" r="10" fill="#606f7b"><animate attributeName="cy" calcMode="spline" values="20;80;20" keyTimes="0;0.5;1" dur="1" keySplines="0.45 0 0.9 0.55;0 0.45 0.55 0.9" begin="0s" repeatCount="indefinite"></animate></circle></svg>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
    new Vue({
    el: '#log-reader',
    data: {
    time: 'today',
    type: 'all',
    loggers: [],
    loading: true
    },
    mounted() {
    this.getLoggers()
    },
    watch: {
    time(value) {
    this.getLoggers();
    },
    type(value) {
    this.getLoggers();
    }
    },
    methods: {
    async getLoggers() {
    this.loading = true
    await this.sleep(1000)
    await axios.get('/'+'{{ config("logreader.prefix") }}', {
    params: {
    logreader_time: this.time,
    logreader_type: this.type
    }
    }).then(({data}) => {
    this.loading = false
    this.loggers = data
    })
    },
    sleep(time) {
    return new Promise(resolve => {
    setTimeout(() => {
    resolve('resolved')
    }, time);
    })
    }
    }
    })
    </script>
</body>
</html>
