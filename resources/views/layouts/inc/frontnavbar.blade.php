<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Site Title</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <style>
        .nav-link {
            color: white;
            transition: color 0.3s ease;
            font-size: 0.9rem;
            margin: 0 3px; /* Reduced margin */
            display: flex;
            align-items: center;
        }
        .nav-link:hover {
            color: #ffffff !important;
        }
        .nav-link.active {
            color: #ffffff !important;
        }
        .nav-link.active i {
            color: #ffffff;
            transform: scale(1.2);
        }
        .nav-link.disabled {
            color: #6c757d;
            pointer-events: none;
        }

        .nav-link i {
            margin-right: 5px;
        }

        .search-bar {
            flex-grow: 1;
            margin-right: 15px;
        }
        .search-bar input[type="search"] {
            border: none;
            border-radius: 5px 0 0 5px;
            padding: 10px 15px;
            transition: all 0.3s ease;
            color: #343a40;
            background-color: #ffffff;
        }
        .search-bar .input-group-text {
            background-color: #9B1B30;
            border: none;
            color: white;
            transition: background-color 0.3s ease;
            border-radius: 0 5px 5px 0;
        }

        .navbar-nav {
            margin-left: auto;
        }

        .dropdown-menu {
            display: none;
            background-color: #343a40;
            border: none;
            margin-top: 0;
            overflow: hidden;
        }
        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            color: #ffffff;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .dropdown-item:hover {
            background-color: #495057;
            color: #ffffff;
        }
        .dropdown-item:focus {
            outline: none;
            background-color: #495057;
        }

        .nav-item {
            white-space: nowrap;
        }

        .badge {
            margin-left: 5px;
            margin-right: 5px;
        }

        .profile-image {
            width: 1.3rem;
            height: 1.3rem;
            border-radius: 50%;
            margin-right: 5px;
        }

        .fa-user-circle {
            font-size: 1rem;
            color: #ffffff;
            margin-left: -2.5px;
        }

        .dropdown-header {
            font-weight: bold;
            color: #ffffff;
            padding: 0.5rem 1rem;
        }

        .dropdown-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            margin: 0.5rem 0;
        }
        .dropdown-menu {
    scrollbar-width: thin; /* For Firefox */
    scrollbar-color: darkred white; /* For Firefox */
}

.dropdown-menu::-webkit-scrollbar {
    width: 8px; /* Width of the scrollbar */
}

.dropdown-menu::-webkit-scrollbar-track {
    background: white; /* Background of the scrollbar track */
}

.dropdown-menu::-webkit-scrollbar-thumb {
    background-color: darkred; /* Color of the scrollbar thumb */
    border-radius: 10px; /* Rounded corners for the scrollbar thumb */
}
.text-danger {
    color: darkred; /* Dark red color for status */
    font-weight: bold; /* Bold text */
}
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top navbar-dark shadow" style="background: #1a1a1a;">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">
      <img src="{{ asset('assets/images/navbariconn.png') }}" alt="Hound Icon" width="120" height="30" class="d-inline-block align-text-top">
    </a>

    
    <div class="search-bar">
        
      <form action="{{ url('searchproduct') }}" method="POST">
        @csrf
        <div class="input-group">
          <input type="search" class="form-control" id="search_product" name="product_name" required placeholder="Search Product" aria-describedby="basic-addon1">
          <button type="submit" class="input-group-text"><i class="fa fa-search"></i></button>
        </div>
      </form>
    </div>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
            <i class="fa fa-home"></i>
            Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('category') ? 'active' : '' }}" href="{{ url('category') }}">
            <i class="fa fa-list"></i>
            Products
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('cart') ? 'active' : '' }}" href="#" id="cartButton">
            <i class="fa fa-shopping-cart"></i>
            Cart
            <span class="badge badge-pill bg-danger cart-count">0</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('wishlist') ? 'active' : '' }}" href="#" id="wishlistButton">
            <i class="fa fa-heart"></i>
            Wishlist 
            <span class="badge badge-pill bg-success wishlist-count">0</span>
          </a>
          
          <li class="nav-item dropdown">

@if(Auth::check() && Auth::user()->role_as == 0)

<a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fa fa-bell"></i>
    @php
        $notifications = [];
        $statusMessages = [
            1 => 'Completed',
            2 => 'Preparing',
            3 => 'Ready to Deliver',
            4 => 'Shipped',
        ];
        $oneMonthAgo = now()->subMonth();
        $orders = App\Models\Order::where('user_id', Auth::id())
            ->whereIn('status', array_keys($statusMessages))
            ->where('updated_at', '>=', $oneMonthAgo)
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($orders as $order) {
            $productNames = $order->products->pluck('name')->toArray();
            $productList = !empty($productNames) ? implode(', ', $productNames) : 'Unknown Product';

            // Create a notification message based on status
            $statusKey = $order->status;
            if (isset($statusMessages[$statusKey])) {
                $notifications[] = [
                    'message' => 'Your order <strong>' . $productList . '</strong> is now <strong class="text-danger">' . $statusMessages[$statusKey] . '</strong>.',
                    'url' => url('order-status/' . $order->id),
                    'timestamp' => $order->updated_at->diffForHumans(),
                ];
            }
        }

        $notificationCount = count($notifications);
    @endphp
    @if($notificationCount > 0)
        <span class="badge bg-danger">{{ $notificationCount }}</span>
    @endif
</a>
<ul class="dropdown-menu" aria-labelledby="notificationDropdown" style="max-height: 300px; overflow-y: auto; background-color: white; border: none; width: 350px; white-space: normal;">
    <li class="dropdown-header" style="background-color: #f8f9fa; font-weight: bold; color: #333;">Your Notifications</li>

    @if($notificationCount > 0)
        @foreach ($notifications as $notification)
            <li>
                <a class="dropdown-item notification-item" href="{{ $notification['url'] }}" style="color: #333; font-size: 0.9rem; white-space: normal;">
                    {!! $notification['message'] !!}
                    <br>
                    <span class="timestamp" style="float: right; font-size: 0.8rem; color: #6c757d;">{{ $notification['timestamp'] }}</span>
                </a>
            </li>
            <div class="dropdown-divider" style="margin: 0.5rem auto; width: 95%; height: 0.5px; background-color: #e0e0e0;"></div>
        @endforeach
    @else
        <li><span class="dropdown-item-text" style="color: #6c757d; font-size: 0.9rem; padding: 12px 15px; display: block; text-align: center;">No notifications available.</span></li>
    @endif
</ul>

<style>
    /* Specific hover effect for dropdown items */
    .dropdown-menu .notification-item:hover {
        background-color: #f1f1f1; /* Light gray color on hover */
    }

    /* Additional styling for the timestamp */
    .timestamp {
        float: right;
        font-size: 0.8rem;
        color: #6c757d;
    }
</style>

@endif
</li>


        <li class="nav-item">
          <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ url('about') }}">
            <i class="fa fa-info"></i> <!-- Updated icon -->
            About
          </a>
        </li>

        @guest
          @if (Route::has('login'))
            <li class="nav-item">
              <a class="nav-link {{ request()->is('login') ? 'active' : '' }}" href="{{ route('login') }}">
              <i class="fa fa-sign-in-alt"></i>
              {{ __('Login') }}
            </a>
            </li>
          @endif

          @if (Route::has('register'))
            <li class="nav-item">
              <a class="nav-link {{ request()->is('register') ? 'active' : '' }}" href="{{ route('register') }}">
              <i class="fa fa-user-plus"></i>
              {{ __('Register') }}
            </a>
            </li>
          @endif
        @else
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" aria-expanded="false">
                <div style="flex: 1; text-align: center; position: relative;">
                    @if(Auth::check() && Auth::user()->profile_image)
                        <img id="profileImage" src="{{ asset('assets/uploads/userprofile/' . Auth::user()->profile_image) }}" alt="Profile Image" class="profile-image">
                    @else
                        <i class="fa fa-user-circle"></i>
                    @endif
                    {{ Auth::check() ? Auth::user()->name : 'Guest' }}
                </div>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                @if(Auth::check())
                    <li class="dropdown-header">Account</li>
                    <li><a class="dropdown-item" href="{{ url('my-orders') }}"><i class="fa fa-box"></i> My Orders</a></li>
                    <li><a class="dropdown-item" href="{{ url('my-profile') }}"><i class="fa fa-user-circle"></i> My Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out-alt"></i> {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </li>
                @else
                    <li class="dropdown-header">Guest</li>
                    <li><a class="dropdown-item" href="{{ route('login') }}"><i class="fa fa-sign-in-alt"></i> Login</a></li>
                    <li><a class="dropdown-item" href="{{ route('register') }}"><i class="fa fa-user-plus"></i> Register</a></li>
                @endif
            </ul>
        </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>

<!-- Include SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('cartButton').addEventListener('click', function(event) {
        @auth
            window.location.href = "{{ url('cart') }}"; // Redirect to cart if authenticated
        @else
            event.preventDefault(); // Prevent default action
            Swal.fire({
                title: 'Please Log In',
                text: 'You need to be logged in to view your cart. Would you like to log in or create an account?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Log In',
                cancelButtonText: 'Create Account'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login') }}"; // Redirect to login
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = "{{ route('register') }}"; // Redirect to registration
                }
            });
        @endauth
    });

    document.getElementById('wishlistButton').addEventListener('click', function(event) {
        @auth
            window.location.href = "{{ url('wishlist') }}"; // Redirect to wishlist if authenticated
        @else
            event.preventDefault(); // Prevent default action
            Swal.fire({
                title: 'Please Log In',
                text: 'You need to be logged in to view your wishlist. Would you like to log in or create an account?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Log In',
                cancelButtonText: 'Create Account'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login') }}"; // Redirect to login
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = "{{ route('register') }}"; // Redirect to registration
                }
            });
        @endauth
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('.dropdown-toggle').on('click', function(e) {
            e.preventDefault();
            var $dropdownMenu = $(this).next('.dropdown-menu');

            if ($dropdownMenu.is(':visible')) {
                $dropdownMenu.slideUp(250);
                $dropdownMenu.removeClass('show');
            } else {
                $('.dropdown-menu').slideUp(250).removeClass('show');
                $dropdownMenu.slideDown(250);
                $dropdownMenu.addClass('show');
            }
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('.dropdown-menu').slideUp(250).removeClass('show');
            }
        });
    });
</script>

</body>
</html>