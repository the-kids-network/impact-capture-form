<!-- NavBar For Authenticated Users -->
<nav-bar :user="user" inline-template>
    <nav class="navbar navbar-logged-in navbar-expand-md navbar-light sticky-top">
        <div class="container" v-if="user">
             <!-- Branding Image -->
            @include('layout.nav.brand')

            <!-- Toggler -->
            <button class="navbar-toggler navbar-toggler-right" 
                    data-toggle="collapse" data-target="#navBarContent"
                    aria-controls="navBarContent" aria-expanded="false" aria-label="Toggle user options">
                <span class="navbar-toggler-icon"></span>
            </button>
        
            <!-- Collapsable content -->
            <div class="collapse navbar-collapse" id="navBarContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <!-- User Photo / Name -->
                        <a id="userDropdownMenuButton" href="#" role="button" 
                            class="nav-link dropdown-toggle text-center" 
                            data-toggle="dropdown" 
                            aria-expanded="false">
                            <img :src="user.photo" v-if="user.photo" class="nav-profile-photo">
                            <span class="caret"></span>
                        </a>

                        <div role="menu" class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdownMenuButton">
                            <!-- Your Settings -->
                            <a class="dropdown-item" href="/settings">
                                <span class="fa fa-fw fa-btn fa-cog"></span> Your Settings
                            </a>
                            <!-- Support -->
                            <a class="dropdown-item" @click.prevent="showSupportForm" style="cursor: pointer;">
                                <span class="fa fa-fw fa-btn fa-paper-plane"></span> Email Us
                            </a>
                            <!-- Logout -->
                            <a class="dropdown-item" href="/logout">
                                <span class="fa fa-fw fa-btn fa-sign-out-alt"></span> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</nav-bar>
