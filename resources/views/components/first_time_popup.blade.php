@if(Auth::check() && !Auth::user()->first_time)

    <style>
        .material-symbols-outlined {
            font-variation-settings: "FILL" 1, "wght" 400, "GRAD" 0, "opsz" 24;
        }
    </style>
    <div class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center p-4 z-50">
            <div
                class="bg-background-light dark:bg-background-dark rounded-xl shadow-2xl w-full max-w-2xl flex flex-col max-h-[90vh]">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-center gap-4">
                    <h2 class="text-xl font-bold text-center text-gray-900 dark:text-white">
                        Non-Disclosure Agreement
                    </h2>
                    <span class="material-symbols-outlined text-gray-500 dark:text-gray-400">gavel</span>
                </div>
                <div class="p-6 overflow-y-auto space-y-4 text-sm text-gray-600 dark:text-gray-400">
                    {{-- <p>
                        This Non-Disclosure Agreement (NDA) is made and entered into as of
                        [Date], by and between [Researcher's Name], residing at
                        [Researcher's Address] (hereinafter "Researcher"), and
                        [Organization's Name], located at [Organization's Address]
                        (hereinafter "Organization").
                    </p> --}}
                    <p>
                        <strong class="text-gray-800 dark:text-white">Confidentiality Agreement and Conflict of Interest
                            Disclosure:</strong><br>
                        Researcher acknowledges that during the course of their interaction with the Organization's file
                        submission portal, they may have access to confidential and proprietary information, including but
                        not limited to research data, unpublished findings, and sensitive personal information
                        (collectively, "Confidential Information").
                    </p>
                    <p>
                        <strong class="text-gray-800 dark:text-white">Confidentiality:</strong><br>
                        Any written information provided to the WMSU REO that is confidential, privileged, or proprietary
                        in nature shall be identified accordingly. All confidential information (and any copies and notes
                        thereof) shall remain the sole property of the WMSU REO.
                    </p>
                    <p>
                        <strong class="text-gray-800 dark:text-white">Conflict of Interest:</strong><br>
                        It is recognized that the potential for conflict of interest will always exist; however, there is
                        concomitant faith in the ability of the WMSU REO to manage these conflict issues, if any, in such a
                        way that the ultimate outcome of protection of human subject remains.
                    </p>

                        <p>
                            It is the policy of the WMSU REO that no member may participate in the review, comment, or approval
                            of any activity in which he/she has a conflict of interest except to provide information as
                            requested by the WMSU REO.
                        </p>

                        <p>
                            The WMSU REO will immediately disclose any actual or potential conflict of interest that may have
                            in relation to any particular proposal submitted for review, and to abstain from any participation
                            in discussion or recommendation in respect of such proposals.
                        </p>

                    <p class="pt-2 footer_agreement">
                        By clicking "Accept," Researcher acknowledges that they have read,
                        understood, and agree to be bound by the terms and conditions of
                        this Non-Disclosure Agreement.
                    </p>
                </div>


                <div class=" Agreement p-6 bg-gray-50 dark:bg-black/20 rounded-b-xl flex justify-end items-center gap-4">
                    <form action="{{ route('accept.terms') }}" method="POST">
                        @csrf
                        <button
                            class="px-6 py-2 rounded-lg text-sm font-semibold bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            <a href="{{ route('logout') }}">Decline</a>
                        </button>
                        <button type="submit"
                            class="px-6 py-2 rounded-lg text-sm font-semibold bg-primary text-white hover:bg-opacity-90 transition-opacity">
                            Accept
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endif

{{-- card carousel with modal component will be added here --}}