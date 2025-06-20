# Kiosqueeing System Documentation

## Table of Contents
1. [System Overview](#system-overview)
2. [Architecture](#architecture)
3. [Component Analysis](#component-analysis)
   - [Branch Management](#branch-management)
   - [Service Management](#service-management)
   - [Counter Management](#counter-management)
   - [Queue Management](#queue-management)
   - [User Management](#user-management)
   - [Settings Management](#settings-management)
   - [Counter Transaction Interface](#counter-transaction-interface)
   - [Monitor Display](#monitor-display)
4. [Workflows](#workflows)
5. [API Integration](#api-integration)
6. [Future Development](#future-development)

## System Overview

The Kiosqueeing system is a comprehensive queue management solution built with Laravel 12, Livewire 3, and Wire UI. The system is designed to manage queues across multiple branches, services, and counters with role-based access control.

### Key Features

- **Multi-branch Support**: Manage multiple physical locations with unique settings
- **Service Management**: Configure different service types per branch
- **Counter Operations**: Staff interface for serving customers
- **Real-time Queue Display**: Monitor screens showing current and waiting queues
- **Role-based Access**: Different interfaces for admin, staff, and coordinators
- **Comprehensive Queue Tracking**: Full lifecycle management of queue tickets

### Tech Stack

- **Framework**: Laravel 12
- **Frontend**: Livewire 3 + Wire UI
- **Styling**: TailwindCSS
- **Build Tool**: Vite
- **Database**: MySQL/PostgreSQL
- **Real-time Updates**: Livewire's real-time capabilities

## Architecture

The system follows a branch-centric architecture where most entities are associated with branches:

```
Branch
  ├── Services
  ├── Counters
  ├── Queues
  ├── Settings
  └── Users (assigned to)
```

### Database Schema (Core Entities)

- **Branches**: Represents physical locations
- **Services**: Different service types offered at branches
- **Counters**: Service points at branches
- **Queues**: Core queuing system with comprehensive status tracking
- **Users**: Staff management with role-based access
- **Settings**: Branch-specific and global configuration

## Component Analysis

### Branch Management

#### Component: `App\Livewire\Admin\Branches`

![Branch Management](https://via.placeholder.com/800x400?text=Branch+Management+UI)

#### Key Features
- **Full CRUD Operations**: Create, read, update, and delete branch records
- **Branch Statistics**: Shows counts of services, counters, and queues
- **Data Validation**: Ensures unique branch codes and required fields
- **Safety Checks**: Prevents deletion of branches with associated records
- **Responsive UI**: Uses Filament Tables for a clean, modern interface

#### Real-World Scenario
A regional bank is expanding and needs to add a new branch location. The admin logs in and clicks "Create Branch" to add the new "Downtown Branch" with code "DT". They enter the physical address and save the record. The system automatically creates the necessary settings for this branch with default values. Later, when services and counters are added to this branch, the statistics on the branches page will update to reflect these additions.

### Service Management

#### Component: `App\Livewire\Admin\Services`

![Service Management](https://via.placeholder.com/800x400?text=Service+Management+UI)

#### Key Features
- **Branch-Specific Services**: Each service is associated with a specific branch
- **Unique Service Codes**: Ensures unique identification within each branch
- **Service Reset**: Ability to reset ticket numbering for specific services
- **Branch Filtering**: Filter services by branch for easier management
- **Service Statistics**: View number of queues processed per service

#### Real-World Scenario
A government office provides multiple services like passport applications, ID renewals, and visa processing. Each service requires different processing times and specialized staff. The admin creates these services in the system, assigning unique codes (PASS, IDREN, VISA). When citizens arrive, they select the specific service they need, and the system routes them to the appropriate counter based on the service type.

### Counter Management

#### Component: `App\Livewire\Admin\Counters`

![Counter Management](https://via.placeholder.com/800x400?text=Counter+Management+UI)

#### Key Features
- **Counter Status Toggle**: Easily activate or deactivate counters
- **Priority Settings**: Designate certain counters for priority service
- **Break Message Management**: Set custom messages for when counters are on break
- **Service Assignment**: Assign specific services to each counter
- **Counter Statistics**: Track performance metrics per counter

#### Real-World Scenario
A busy hospital outpatient department has 10 registration counters. During peak hours, all counters are active, but during slower periods, some counters can be deactivated. Counter #1 is designated as a priority counter for elderly and disabled patients. When staff take breaks, they can set custom messages like "Back in 15 minutes" or "Lunch break until 1:00 PM" to inform waiting patients.

### Queue Management

#### Component: `App\Livewire\Admin\Queues`

![Queue Management](https://via.placeholder.com/800x400?text=Queue+Management+UI)

#### Key Features
- **Comprehensive Status Management**: Track queues through their entire lifecycle
- **Status Transitions**: Waiting → Called → Serving → Completed
- **Additional States**: Hold, Skip, Cancel, Expire
- **Filtering Options**: Filter by branch, service, status, date
- **Counter Reassignment**: Move queues between counters as needed
- **Hold with Reason**: Place queues on hold with explanatory notes

#### Real-World Scenario
A customer arrives at a bank and takes a queue ticket for account services. The admin can see this ticket in the waiting status. When counter #3 calls this customer, the status changes to "called." If the customer doesn't appear, the staff can mark it as "skipped" or "expired." If the customer needs to get additional documents, the staff can put the ticket on "hold" with a reason note. The admin has full visibility of all these status changes and can intervene if needed, such as reassigning a ticket to a different counter.

### User Management

#### Component: `App\Livewire\Admin\Users`

![User Management](https://via.placeholder.com/800x400?text=User+Management+UI)

#### Key Features
- **Role-based Access**: Admin, staff, and coordinator roles
- **Branch Assignment**: Assign users to specific branches
- **Password Management**: Secure creation and reset functionality
- **User Statistics**: Track performance metrics for each user
- **Self-deletion Protection**: Prevent users from deleting their own accounts

#### Real-World Scenario
A new employee joins the organization and needs system access. The admin creates a new user account, assigns the appropriate role (staff), and assigns them to their branch location. The system generates a secure initial password that the user must change on first login. If the employee transfers to a different branch, the admin can update their branch assignment without creating a new account.

### Settings Management

#### Component: `App\Livewire\Admin\Settings` and `App\Livewire\Admin\BranchQueueSettings`

![Settings Management](https://via.placeholder.com/800x400?text=Settings+Management+UI)

#### Key Features
- **Branch-specific Settings**: Customize settings per branch
- **Global Defaults**: Fallback settings when branch-specific ones aren't set
- **Queue Number Formatting**: Configure ticket number prefixes and base numbers
- **Queue Reset Time**: Set automatic daily reset time for queue numbers
- **Visual Indicators**: Clear display of which settings are branch-specific vs. global

#### Real-World Scenario
A multi-location business wants each branch to have its own ticket numbering system. The admin configures Branch A to use the prefix "A-" and Branch B to use "B-" for their tickets. They also set Branch A to reset its queue numbers at 8:00 AM (opening time) and Branch B at 9:00 AM (its opening time). When a setting isn't specified for a branch, the system automatically uses the global default value.

### Counter Transaction Interface

#### Component: `App\Livewire\Counter\CounterTransactionPage`

![Counter Transaction](https://via.placeholder.com/800x400?text=Counter+Transaction+UI)

#### Key Features
- **Queue Selection**: Staff can select which customer to serve next
- **Status Management**: Change queue status (serve, complete, hold, skip)
- **Counter Status Toggle**: Switch between active and break modes
- **Hold with Reason**: Place customers on hold with explanatory notes
- **Real-time Updates**: See new waiting tickets as they enter the queue

#### Real-World Scenario
A staff member logs in and selects their assigned counter. They see three waiting customers in the queue and click "Call Next" to serve the first one. The customer's number appears on the display screen. After serving the customer, they click "Complete" to mark the transaction as finished. If they need a break, they can click "Start Break" and enter a message like "Back in 15 minutes." When they return, they click "Resume Work" to start serving customers again.

### Monitor Display

#### Component: `App\Livewire\Monitor\DisplayPage`

![Monitor Display](https://via.placeholder.com/800x400?text=Monitor+Display+UI)

#### Key Features
- **Now Serving Display**: Shows currently active tickets and their counters
- **Waiting Queue Grid**: Displays upcoming ticket numbers in an organized grid
- **Multiple Counter Support**: Handles multiple counters serving simultaneously
- **Visual Styling**: High-contrast design optimized for visibility on large screens
- **Real-time Updates**: Automatically refreshes as queue statuses change

#### Real-World Scenario
A large waiting area has multiple display screens showing the queue status. The top section shows "NOW SERVING" with counter numbers and ticket numbers (e.g., "Counter 1: A-105", "Counter 3: A-106"). Below this, a grid shows the next 8-12 waiting ticket numbers. When a counter calls a new ticket, the display updates automatically, moving that ticket from the waiting grid to the "NOW SERVING" section. This gives waiting customers clear visibility of their position in the queue.

## Workflows

### Customer Journey
1. Customer arrives and selects desired service at kiosk
2. System generates ticket with unique number
3. Customer waits in waiting area, watching monitor display
4. When their number is called, display shows which counter to approach
5. Staff serves the customer and marks transaction as complete
6. Customer leaves with their needs fulfilled

### Staff Journey
1. Staff logs in and selects their assigned counter
2. They view waiting customers and call the next in line
3. They serve the customer and update status accordingly
4. If needed, they can put customers on hold or skip them
5. They can toggle break status when needed
6. At end of shift, they log out from the counter

### Admin Journey
1. Admin configures branches, services, and counters
2. They manage user accounts and permissions
3. They monitor queue statistics and performance metrics
4. They adjust settings as needed for optimal operation
5. They can intervene in queue management when necessary

## API Integration

The system includes a robust API response structure through the `ApiResponse` helper class, providing consistent JSON responses for potential integration with:

- Mobile applications for virtual queue management
- SMS notification systems for queue status updates
- Digital signage systems for additional displays
- Analytics platforms for performance monitoring

### Example API Response
```json
{
  "status": true,
  "message": "Queue created successfully",
  "data": {
    "id": 123,
    "branch": "Main Branch",
    "service": "Passport Application",
    "number": 42,
    "ticket_number": "PA-042",
    "created_at": "2025-06-20 10:30:15",
    "status": "waiting"
  }
}
```

## Future Development

### Kiosk Implementation
The next phase of development will focus on implementing the kiosk interface for customer self-service. This will include:

- Touchscreen-friendly service selection interface
- Ticket printing functionality
- QR code generation for digital tickets
- Multi-language support for diverse customer base
- Accessibility features for users with disabilities

### Mobile Integration
Future plans include developing a mobile application that allows customers to:

- Join queues remotely before arriving
- Receive real-time updates on queue status
- Get notifications when their turn is approaching
- Provide feedback on service quality

### Analytics Dashboard
An enhanced analytics dashboard will provide insights into:

- Peak hours and staffing optimization
- Service time averages and outliers
- Customer flow patterns
- Staff performance metrics
- Branch comparison statistics
