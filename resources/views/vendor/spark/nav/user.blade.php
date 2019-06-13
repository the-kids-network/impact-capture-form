<!-- NavBar For Authenticated Users -->
<spark-navbar
    :user="user"
    inline-template>

    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container" v-if="user">
            <div class="navbar-header">
                <!-- Collapsed Hamburger -->
                <div class="hamburger">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#spark-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <!-- Branding Image -->
                @include('spark::nav.brand')
            </div>

            <div class="collapse navbar-collapse" id="spark-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    @includeIf('spark::nav.user-left')
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    @includeIf('spark::nav.user-right')

                    <li class="dropdown">
                        <!-- User Photo / Name -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <img :src="user.photo_url" class="spark-nav-profile-photo m-r-xs">
                            <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <!-- Settings -->
                            <li class="dropdown-header">Settings</li>

                            <!-- Your Settings -->
                            <li>
                                <a href="/settings">
                                    <i class="fa fa-fw fa-btn fa-cog"></i>Your Settings
                                </a>
                            </li>

                            <li class="divider"></li>

                            @if (Spark::hasSupportAddress())
                                <!-- Support -->
                                @include('spark::nav.support')
                            @endif

                            <!-- Logout -->
                            <li>
                                <a href="/logout">
                                    <i class="fa fa-fw fa-btn fa-sign-out"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</spark-navbar>
