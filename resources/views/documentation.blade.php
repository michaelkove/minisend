@extends('layout')
@section('content')
    <div class="row">
        <div class="col-12">

            <h1>Api Documentation (DEMO)</h1>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Users:</h5>
                    <p class="card-text">Endpoint READ: <strong>/user/{id}</strong></p>
                    <h6>Sample Input</h6>
                    <code>
                    <pre>
                        {
                            id : 1
                        }
                    </pre>
                    </code>
                    <h6>Sample Output</h6>
                    <code>
                    <pre>
                        {
                            created_at: "2020-11-12T07:56:41.000000Z"
                            email: "caleigh67@example.com"
                            email_verified_at: "2020-11-12T07:56:41.000000Z"
                            id: 1
                            name: "Ms. Ottilie Casper MD"
                            updated_at: "2020-11-12T07:56:41.000000Z"
                        }
                    </pre>
                    </code>
                </div>
                <div class="card-footer">
                    <small class="text-muted"></small>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Users Email:</h5>
                    <p class="card-text">Endpoint GET: <strong>/user/{id}/email?page=x</strong></p>
                    <h6>Sample Input</h6>
                    <code>
                    <pre>
                        {
                            user_id : 1
                        }
                    </pre>
                    </code>
                    <h6>Sample Output</h6>
                    <code>
                    <pre>
                        {
                            emails: {
                                current_page: 1
                                data: [
                                    {
                                        attachments: [
                                            {
                                                created_at: "2020-11-13T08:26:39.000000Z"
                                                email_id: 50
                                                filename: "attachments/Sk0w1t4XSllfU1vw3e1ACOqe4ZdBGEiyzznJjmez.txt"
                                                id: 32
                                                name: "somemorestufff.txt"
                                                type: "text/plain"
                                                updated_at: "2020-11-13T08:26:39.000000Z"
                                            }
                                        ]
                                        body_html: "test html"
                                        body_text: "test body"
                                        created_at: "2020-11-12T07:57:41.000000Z"
                                        id: 1
                                        recipients: [
                                            {
                                                created_at: "2020-11-12T07:57:42.000000Z"
                                                email: "mkovalch@gmail.com"
                                                first_name: null
                                                id: 1
                                                last_name: null
                                                middle_name: null
                                                pivot: {
                                                    email_id: 1
                                                    recipient_id: 1
                                                    status: "posted"
                                                }
                                                email_id: 1
                                                recipient_id: 1
                                                status: "posted"
                                                updated_at: "2020-11-12T07:57:42.000000Z"
                                                user_id: 1
                                            }
                                        ]
                                        subject: "Test Email For Nobody"
                                        updated_at: "2020-11-12T07:57:41.000000Z"
                                        user: {
                                            created_at: "2020-11-12T07:56:41.000000Z"
                                            email: "caleigh67@example.com"
                                            email_verified_at: "2020-11-12T07:56:41.000000Z"
                                            id: 1
                                            name: "Ms. Ottilie Casper MD"
                                            updated_at: "2020-11-12T07:56:41.000000Z"
                                        }
                                        user_id: 1
                                    }

                                ]
                                first_page_url: "http://localhost/api/user/1/email?page=1"
                                from: 1
                                last_page: 2
                                last_page_url: "http://localhost/api/user/1/email?page=2"
                                links: [
                                            {
                                                url: null,
                                                label: "&laquo; Previous",
                                                active: false
                                            },…]
                                next_page_url: "http://localhost/api/user/1/email?page=2"
                                path: "http://localhost/api/user/1/email"
                                per_page: 50
                                prev_page_url: null
                                to: 50
                                total: 62
                            }

                            messages: [
                                [
                                    {
                                        'message' : "Message Text",
                                        'type'     : "info"
                                ]
                            ]
                        }
                    </pre>
                    </code>
                </div>
                <div class="card-footer">
                    <small class="text-muted"></small>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Search For Users Email:</h5>
                    <p class="card-text">Endpoint GET: <strong>/user/{id}/email/search?page=x</strong></p>
                    <h6>Sample Input</h6>
                    <code>
                    <pre>
                        {
                            user_id : 1
                            q : "query",
                            location : "all"
                        }
                    </pre>
                    </code>
                    <h6>Sample Output</h6>
                    <code>
                    <pre>
                        {
                            emails: {
                                current_page: 1
                                data: [
                                    {
                                        attachments: [
                                            {
                                                created_at: "2020-11-13T08:26:39.000000Z"
                                                email_id: 50
                                                filename: "attachments/Sk0w1t4XSllfU1vw3e1ACOqe4ZdBGEiyzznJjmez.txt"
                                                id: 32
                                                name: "somemorestufff.txt"
                                                type: "text/plain"
                                                updated_at: "2020-11-13T08:26:39.000000Z"
                                            }
                                        ]
                                        body_html: "test html"
                                        body_text: "test body"
                                        created_at: "2020-11-12T07:57:41.000000Z"
                                        id: 1
                                        recipients: [
                                            {
                                                created_at: "2020-11-12T07:57:42.000000Z"
                                                email: "mkovalch@gmail.com"
                                                first_name: null
                                                id: 1
                                                last_name: null
                                                middle_name: null
                                                pivot: {
                                                    email_id: 1
                                                    recipient_id: 1
                                                    status: "posted"
                                                }
                                                email_id: 1
                                                recipient_id: 1
                                                status: "posted"
                                                updated_at: "2020-11-12T07:57:42.000000Z"
                                                user_id: 1
                                            }
                                        ]
                                        subject: "Test Email For Nobody"
                                        updated_at: "2020-11-12T07:57:41.000000Z"
                                        user: {
                                            created_at: "2020-11-12T07:56:41.000000Z"
                                            email: "caleigh67@example.com"
                                            email_verified_at: "2020-11-12T07:56:41.000000Z"
                                            id: 1
                                            name: "Ms. Ottilie Casper MD"
                                            updated_at: "2020-11-12T07:56:41.000000Z"
                                        }
                                        user_id: 1
                                    }

                                ]
                                first_page_url: "http://localhost/api/user/1/email?page=1"
                                from: 1
                                last_page: 2
                                last_page_url: "http://localhost/api/user/1/email?page=2"
                                links: [
                                            {
                                                url: null,
                                                label: "&laquo; Previous",
                                                active: false
                                            },…]
                                next_page_url: "http://localhost/api/user/1/email?page=2"
                                path: "http://localhost/api/user/1/email"
                                per_page: 50
                                prev_page_url: null
                                to: 50
                                total: 62
                            }

                            messages: [
                                [
                                    {
                                        'message' : "Message Text",
                                        'type'     : "info"
                                ]
                            ]
                        }
                    </pre>
                    </code>
                </div>
                <div class="card-footer">
                    <small class="text-muted"></small>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Create User Email:</h5>
                    <p class="card-text">Endpoint POST: <strong>/user/{id}/email</strong></p>
                    <h6>Sample Input</h6>
                    <code>
                    <pre>
                        {
                            _token: OjSMh30f0002cbipAvKlWDmdbtzG1JV98EFVAzdy
                            _method: POST
                            subject: "Subject"
                            body_text: "Html Body text"
                            body_html: "TEXT"
                            attachments[0]: (binary)
                            recipients[0][email]: to@example.com
                        }
                    </pre>
                    </code>
                    <h6>Sample Output</h6>
                    <code>
                    <pre>
                        {

                            messages: [
                                [
                                    {
                                        'message' : "Email Posted",
                                        'type'     : "info"
                                ]
                            ]
                        }
                    </pre>
                    </code>
                </div>
                <div class="card-footer">
                    <small class="text-muted"></small>
                </div>
            </div>
        </div>
    </div>
@endsection
