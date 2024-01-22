<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="{{url('/')}}">Insights Dashboard</a>
      <div class="searcha">
    </div>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          @guest
              <li class="nav-item">
                <a href="{{url('my_orders')}}" class="nav-link active" >My Orders</a>
              </li>
          @endguest

        </ul>
      </div>
    </div>
  </nav>