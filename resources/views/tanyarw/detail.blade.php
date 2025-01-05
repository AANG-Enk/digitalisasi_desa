@extends('layouts.backend.main')

@section('pagecss')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-chat.css') }}" />
@endsection

@section('title')
    Tanya RW - Digitalisasi Desa
@endsection

@section('content')
<div class="d-flex justify-content-end">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">Dashboard</a>
          </li>
          @if (isset($tanyarw))
            <li class="breadcrumb-item">
                <a href="{{ route('tanyarw.index') }}">Tanya RW</a>
            </li>
            <li class="breadcrumb-item active">Pertanyaan {{ $tanyarw->pembuat->name }}</li>
          @else
            <li class="breadcrumb-item active">Tanya RW</li>
          @endif
        </ol>
    </nav>
</div>

<div class="app-chat card overflow-hidden">
    <div class="row g-0">
      <!-- Chat History -->
      <div class="col app-chat-history">
        <div class="chat-history-wrapper">
          <div class="chat-history-header border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex overflow-hidden align-items-center">
                    <i
                    class="ri-menu-line ri-24px cursor-pointer d-lg-none d-block me-4"
                    data-bs-toggle="sidebar"
                    data-overlay
                    data-target="#app-chat-contacts"></i>
                    <div class="flex-shrink-0 avatar avatar-online">
                    <img
                        src="{{ asset('assets/img/avatars/4.png') }}"
                        alt="Avatar"
                        class="rounded-circle"
                        data-bs-toggle="sidebar"
                        data-overlay
                        data-target="#app-chat-sidebar-right" />
                    </div>
                    <div class="chat-contact-info flex-grow-1 ms-4">
                    <h6 class="m-0 fw-normal">{{ (isset($tanyarw))?$tanyarw->pembuat->name:'Pak RW' }}</h6>
                    </div>
                </div>
            </div>
          </div>
          <div class="chat-history-body">
            <ul class="list-unstyled chat-history">
                @foreach ($list_tanya_rw as $item)
                    @php
                        if(is_null($item->warga_text)){
                            $text = $item->rw_text;
                        }elseif(is_null($item->rw_text)){
                            $text = $item->warga_text;
                        }
                    @endphp
                    <li class="chat-message {{ (!is_null($item->warga_text))?'chat-message-right':'' }}">
                        <div class="d-flex overflow-hidden">
                            <div class="chat-message-wrapper flex-grow-1">
                                <div class="chat-message-text">
                                <p class="mb-0">{!! $text !!}</p>
                                </div>
                                <div class="text-end text-muted mt-1">
                                <i class="ri-check-double-line ri-14px {{ ($item->is_read)?'text-success':'text-secondary' }} me-1"></i>
                                    <small>{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}</small>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
          </div>
          <!-- Chat message form -->
          <div class="chat-history-footer">
            <form class="form-send-message d-flex justify-content-between align-items-center" action="{{ $action }}" method="POST">
                @isset($tanyarw)
                    @method('PUT')
                @endisset
                @csrf
                <input
                class="form-control message-input me-4 shadow-none @error('text') is-invalid @enderror"
                name="text"
                value="{{ old('text') }}"
                placeholder="Ketik disini...." />
                @error('text')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
              <div class="message-actions d-flex align-items-center">
                <button class="btn btn-primary d-flex send-msg-btn">
                  <span class="align-middle">Kirim</span>
                  <i class="ri-send-plane-line ri-16px ms-md-2 ms-0"></i>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- /Chat History -->

        <div class="app-overlay"></div>
    </div>
</div>

@endsection

@section('pagejs')
    <script>
        $(document).ready(function(){

            const chatContactsBody = document.querySelector('.app-chat-contacts .sidebar-body'),
            chatContactListItems = [].slice.call(
                document.querySelectorAll('.chat-contact-list-item:not(.chat-contact-list-item-title)')
            ),
            chatHistoryBody = document.querySelector('.chat-history-body'),
            chatSidebarLeftBody = document.querySelector('.app-chat-sidebar-left .sidebar-body'),
            chatSidebarRightBody = document.querySelector('.app-chat-sidebar-right .sidebar-body'),
            chatUserStatus = [].slice.call(document.querySelectorAll(".form-check-input[name='chat-user-status']")),
            chatSidebarLeftUserAbout = $('.chat-sidebar-left-user-about'),
            formSendMessage = document.querySelector('.form-send-message'),
            messageInput = document.querySelector('.message-input'),
            searchInput = document.querySelector('.chat-search-input'),
            speechToText = $('.speech-to-text'), // ! jQuery dependency for speech to text
            userStatusObj = {
                active: 'avatar-online',
                offline: 'avatar-offline',
                away: 'avatar-away',
                busy: 'avatar-busy'
            };

            // Initialize PerfectScrollbar
            // ------------------------------

            // Chat contacts scrollbar
            if (chatContactsBody) {
            new PerfectScrollbar(chatContactsBody, {
                wheelPropagation: false,
                suppressScrollX: true
            });
            }

            // Chat history scrollbar
            if (chatHistoryBody) {
            new PerfectScrollbar(chatHistoryBody, {
                wheelPropagation: false,
                suppressScrollX: true
            });
            }

            // Sidebar left scrollbar
            if (chatSidebarLeftBody) {
            new PerfectScrollbar(chatSidebarLeftBody, {
                wheelPropagation: false,
                suppressScrollX: true
            });
            }

            // Sidebar right scrollbar
            if (chatSidebarRightBody) {
            new PerfectScrollbar(chatSidebarRightBody, {
                wheelPropagation: false,
                suppressScrollX: true
            });
            }

            // Scroll to bottom function
            function scrollToBottom() {
            chatHistoryBody.scrollTo(0, chatHistoryBody.scrollHeight);
            }
            scrollToBottom();

        })
    </script>
@endsection
