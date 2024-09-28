<!-- App Bottom Menu -->
</style>
<div class="appBottomMenu">
    <a href="/dashboard" class="item {{ request()->is('dashboard') ? 'active' : '' }}">
        <div class="col">
            <span title="Home"><ion-icon name="home-outline"></ion-icon></span>
            <strong>Home</strong>
        </div>
    </a>
    <a href="/histori/histori" class="item {{ request()->is('histori/histori') ? 'active' : '' }}">
        <div class="col">
            <span title="Histori"><ion-icon name="document-text-outline" role="img" class="md hydrated"
                    aria-label="document text outline"></ion-icon></span>
            <strong>Histori</strong>
        </div>
    </a>
    <a href="/Absensi/pilihabsen" class="item">
        <div class="col">
            <div class="action-button large">
                <ion-icon name="camera" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
            </div>
        </div>
    </a>
    <a href="/izin/izin" class="item {{ request()->is('#') ? 'active' : '' }}">
        <div class="col">
            <span title="Izin"><ion-icon name="calendar-outline" role="img" class="md hydrated"
                    aria-label="calendar outline"></ion-icon></span>
            <strong>Izin</strong>
        </div>
    </a>
    <a href="/editprofil" class="item {{ request()->is('editprofil') ? 'active' : '' }}">
        <div class="col">
            <span title="Profil"><ion-icon name="people-outline" role="img" class="md hydrated"
                    aria-label="people outline">
                </ion-icon></span>
            <strong>Profile</strong>
        </div>
    </a>
</div>
<!-- * App Bottom Menu -->
