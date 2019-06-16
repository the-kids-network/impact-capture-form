<!-- NavBar For Authenticated Users -->
<nav-bar :user="user" inline-template>

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
                @include('layout.nav.brand')
            </div>

            <div class="collapse navbar-collapse" id="spark-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    @includeIf('layout.nav.user-left')
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    @includeIf('layout.nav.user-right')

                    <li class="dropdown">
                        <!-- User Photo / Name -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <img :src="user.photo" v-if="user.photo" class="spark-nav-profile-photo m-r-xs">
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

                            @include('layout.nav.support')

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
</nav-bar>
