<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Log Reader</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@0.7.4/dist/tailwind.min.css" rel="stylesheet">
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
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <title>synchronize-arrow-clock</title>
                                <path d="M12.965,6a1,1,0,0,0-1,1v5.5a1,1,0,0,0,1,1h5a1,1,0,0,0,0-2h-3.75a.25.25,0,0,1-.25-.25V7A1,1,0,0,0,12.965,6Z" />
                                <path d="M12.567,1.258A10.822,10.822,0,0,0,2.818,8.4a.25.25,0,0,1-.271.163L.858,8.309a.514.514,0,0,0-.485.213.5.5,0,0,0-.021.53l2.679,4.7a.5.5,0,0,0,.786.107l3.77-3.746a.5.5,0,0,0-.279-.85L5.593,9.007a.25.25,0,0,1-.192-.35,8.259,8.259,0,1,1,7.866,11.59,1.25,1.25,0,0,0,.045,2.5h.047a10.751,10.751,0,1,0-.792-21.487Z" />
                            </svg>
                        </div>
                        <select v-model="time" class="block appearance-none ml-2 w-full rounded-none text-sm border-b border-grey-darker text-grey-darker bg-white py-1 px-4 pr-8 leading-tight focus:outline-none" id="grid-state">
                            <option value="today">Today</option>
                            <option value="yesterday">Yesterday</option>
                            <option value="all">All</option>
                        </select>
                        <div class="pointer-events-none absolute pin-y pin-r flex items-center text-grey">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="mr-6">
                    <div class="relative">
                        <div class="pointer-events-none absolute pin-y pin-l flex items-center text-grey">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <title>filter-1</title>
                                <path d="M22.826,3.58A2.251,2.251,0,0,0,21.01,0H2.992A2.25,2.25,0,0,0,1.164,3.563l7.1,10.171v8.015A2.259,2.259,0,0,0,10.716,24a2.9,2.9,0,0,0,1.573-.408c2.733-1.7,2.445-1.524,2.471-1.543a2.261,2.261,0,0,0,.9-1.8V13.737Zm-9.437,9.04a1.25,1.25,0,0,0-.229.721v6.481a.5.5,0,0,1-.244.43c-.328.194-.923.547-1.405.827a.5.5,0,0,1-.751-.432V13.341a1.242,1.242,0,0,0-.225-.715L4.018,3.287a.5.5,0,0,1,.41-.786H19.563a.5.5,0,0,1,.409.788Z" />
                            </svg>
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
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <table class="mb-20 w-full flex flex-col w-full text-left table-collapse bg-white rounded-b-lg shadow">
            <thead class="flex w-full">
                <tr class="flex w-full">
                    <th class="flex-1 text-sm text-grey-lighter py-4 px-3 bg-grey-darker rounded-tl-lg">DATE</th>
                    <th class="w-1/6 text-sm text-grey-lighter py-4 px-3 bg-grey-darker">TYPE</th>
                    <th class="w-3/5 lex text-sm text-grey-lighter py-4 px-3 bg-grey-darker rounded-tr-lg">MESSAGE</th>
                </tr>
            </thead>
            <tbody class="align-baseline">
                <tr v-if="!loading" v-for="logger in loggers" class="flex w-full">
                    <td class="flex-1 p-4 border-t border-grey-light text-sm text-grey-darkest whitespace-no-wrap break-words">@{{ new Date(logger['date']).toUTCString() }}</td>
                    <td class="w-1/6 p-4 border-t border-grey-light text-sm text-grey-darkest whitespace-no-wrap">@{{ logger['type'] }}</td>
                    <td class="w-3/5 flex p-4 border-t border-grey-light text-sm text-red-darker break-words leading-normal relative">
                        <div class="w-full overflow-wrap">
                            <div>
                                @{{ logger['message'] }}
                            </div>
                            <div v-if="logger.extra.length" class="absdolute w-full pin-b pin-r flex justify-end">
                                <button @click="showModal(logger)" class="appearance-none text-blue-light text-xs hover:underline">More Info</button>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div v-if="modal" class="fixed pin z-50 overflow-auto flex" style="background-color: rgba(0, 0, 0, 0.4);">
            <div class="relative p-8 bg-white w-full max-w-lg m-auto flex-col flex rounded border shadow" style="height: 32rem;">
                <span class="absolute pin-t pin-b pin-r p-4">
                    <svg @click="closeModal()" class="h-3 w-3 fill-current text-grey hover:text-grey-darkest" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                </span>
                <table v-if="currentLogger" class="flex flex-col h-auto overflow-auto w-full text-left table-fixed table-collapse bg-white rounded-b-lg">
                    <tbody class="align-baseline">
                        <tr v-if="!loading" v-for="extra in currentLogger.extra" class="flex">
                            <td class="w-1/2 break-words text-grey-darker border-t border-grey-light p-3 text-sm">
                                <div>
                                    @{{ extra[0] }}
                                </div>
                            </td>
                            <td class="w-1/2 break-words text-red-darker  border-t border-grey-light p-3 text-sm">
                                <div>
                                    @{{ extra[1] }}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div v-if="loggers.length === 0 && ! loading">
            <h3 class="font-thin text-xl text-center">
                @{{ message || 'Nothing is Logged !'}}
            </h3>
        </div>

        <div v-if="loading" class="mt-10 w-full flex items-center justify-center">
            <svg class="lds-gear" width="24px"  height="24px"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" style="background: none;"><g transform="translate(50 50)">
                <g transform="rotate(209.963)">
                <animateTransform attributeName="transform" type="rotate" values="0;360" keyTimes="0;1" dur="1s" repeatCount="indefinite"></animateTransform><path d="M37.43995192304605 -6.5 L47.43995192304605 -6.5 L47.43995192304605 6.5 L37.43995192304605 6.5 A38 38 0 0 1 35.67394948182593 13.090810836924174 L35.67394948182593 13.090810836924174 L44.33420351967032 18.090810836924174 L37.83420351967032 29.34914108612188 L29.17394948182593 24.34914108612188 A38 38 0 0 1 24.34914108612188 29.17394948182593 L24.34914108612188 29.17394948182593 L29.34914108612188 37.83420351967032 L18.090810836924184 44.33420351967032 L13.090810836924183 35.67394948182593 A38 38 0 0 1 6.5 37.43995192304605 L6.5 37.43995192304605 L6.500000000000001 47.43995192304605 L-6.499999999999995 47.43995192304606 L-6.499999999999996 37.43995192304606 A38 38 0 0 1 -13.09081083692417 35.67394948182593 L-13.09081083692417 35.67394948182593 L-18.09081083692417 44.33420351967032 L-29.34914108612187 37.834203519670325 L-24.349141086121872 29.173949481825936 A38 38 0 0 1 -29.17394948182592 24.34914108612189 L-29.17394948182592 24.34914108612189 L-37.83420351967031 29.349141086121893 L-44.33420351967031 18.0908108369242 L-35.67394948182592 13.090810836924193 A38 38 0 0 1 -37.43995192304605 6.5000000000000036 L-37.43995192304605 6.5000000000000036 L-47.43995192304605 6.500000000000004 L-47.43995192304606 -6.499999999999993 L-37.43995192304606 -6.499999999999994 A38 38 0 0 1 -35.67394948182593 -13.090810836924167 L-35.67394948182593 -13.090810836924167 L-44.33420351967032 -18.090810836924163 L-37.834203519670325 -29.34914108612187 L-29.173949481825936 -24.34914108612187 A38 38 0 0 1 -24.349141086121893 -29.17394948182592 L-24.349141086121893 -29.17394948182592 L-29.349141086121897 -37.834203519670304 L-18.0908108369242 -44.334203519670304 L-13.090810836924195 -35.67394948182592 A38 38 0 0 1 -6.500000000000005 -37.43995192304605 L-6.500000000000005 -37.43995192304605 L-6.500000000000007 -47.43995192304605 L6.49999999999999 -47.43995192304606 L6.499999999999992 -37.43995192304606 A38 38 0 0 1 13.090810836924149 -35.67394948182594 L13.090810836924149 -35.67394948182594 L18.090810836924142 -44.33420351967033 L29.349141086121847 -37.83420351967034 L24.349141086121854 -29.17394948182595 A38 38 0 0 1 29.17394948182592 -24.349141086121893 L29.17394948182592 -24.349141086121893 L37.834203519670304 -29.349141086121897 L44.334203519670304 -18.0908108369242 L35.67394948182592 -13.090810836924197 A38 38 0 0 1 37.43995192304605 -6.500000000000007 M0 -20A20 20 0 1 0 0 20 A20 20 0 1 0 0 -20" fill="#c2c2c2"></path></g></g>
            </svg>
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
      loading: true,
      message:  null,
      currentLogger: null,
      modal: false
    },
    mounted () {
      this.getLoggers()
    },
    watch: {
      time (value) {
        this.getLoggers()
      },
      type (value) {
        this.getLoggers()
      }
    },
    methods: {
      async getLoggers () {
        this.message = null
        this.loading = true

        await this.sleep(200)

        await axios.get('/' + '{{ config("logreader.prefix", "logreader") }}', {
          params: {
            logreader_time: this.time,
            logreader_type: this.type
          }
        }).then(({data}) => {
          this.loggers = data
        }).catch(() => {
            this.loggers = []
            this.message = 'Something Went Wrong!'
        })

        this.loading = false
      },

      showModal (logger) {
        this.currentLogger = logger

        this.modal = true
      },

      closeModal () {
        this.modal = false

        this.currentLogger = null;
      },

      sleep (time) {
        return new Promise(resolve => {
          setTimeout(() => {
            resolve('resolved')
          }, time)
        })
      }
    }
  })
</script>
</body>
</html>
