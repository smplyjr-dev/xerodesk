const modules = [
  "Employees – Onboarding/Employee Adhoc",
  "Employees - Verification Account",
  "Employees - Entitlement",
  "Organization Module",
  "Timesheet - Holidays Module",
  "Timesheet - Timesheet Policies Module",
  "Timesheet - Work Schedules Module",
  "Timesheet - FiliClock Module",
  "Timesheet - Biometrics Module ",
  "Timesheet - Timesheet Module",
  "Timesheet - Change in Shift Module",
  "Timesheet - Offset Module",
  "Timesheet - Overtime Module",
  "Timesheet - Official Business Travel Module",
  "Timesheet - DTR Request Module",
  "Leave - Leave Policies Module",
  "Leave - Entitlement Module",
  "Leave - Leave Request Module",
  "FiliPayroll Module",
  "Certificates Module",
  "Reports Module",
  "Operations – Employee Roles Module",
  "Operations – Audit Trail Module",
  "Global Settings Module",
  "Documents Module",
  "Team Insights Module",
  "Others"
];

const tickets = {
  priority: [
    { id: 1, name: "Low" },
    { id: 2, name: "Medium" },
    { id: 3, name: "High" },
    { id: 4, name: "Urgent" }
  ],
  status: [
    { id: 1, name: "Open" },
    { id: 2, name: "Pending" },
    { id: 3, name: "Resolved" },
    { id: 4, name: "Closed" },
    { id: 5, name: "Waiting for Agent" },
    { id: 6, name: "Waiting for Client" }
  ]
};

export { modules, tickets };
