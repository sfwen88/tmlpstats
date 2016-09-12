import { objectAssign } from '../reusable/ponyfill'
import Api from '../api'

import Reports from './reports-generated'

const { $ } = window

window.showReportView = function(config) {
    const fullReport = Reports[config.report]
    const target = $(config.target)
    const reportApi = Api[config.report + 'Report']

    var loadQueue = []
    var loaded = {}

    /// SHOW REPORT OF CHOOSING. ACTUAL AJAX STUFF
    function showReport(cid) {
        const report = fullReport.children[cid]
        if (report && report.type != 'grouping') {
            console.log('would show report', report.id, report.name)
            queueLoad(cid)
            console.log('order is now', loadQueue.join(','))
        } else {
            console.log('avoiding report', cid)
        }
    }

    /// Manage Load Queue
    function queueLoad(id) {
        const idx = loadQueue.indexOf(id)
        if (idx != -1) {
            loadQueue.splice(idx, 1)
        }
        loadQueue.push(id)
    }

    var NUM_LOAD = 1

    function loadNext() {
        var pages = []
        for (var i = 0; i < NUM_LOAD; i++) {
            if (!loadQueue.length) {
                break
            }
            let page = loadQueue.pop()
            if (!loaded[page]) {
                pages.push(page)
            }
        }

        if (!pages.length) {
            return
        }
        console.log('about to actually load', pages.join(', '))
        const params = objectAssign({pages: pages}, config.params)
        reportApi.getReportPages(params).then(function(data) {
            // if no error, then increase load time
            if (NUM_LOAD < 5) {
                NUM_LOAD++
            }
            pages.forEach((page) => {
                console.log('loaded page', page)
                loaded[page] = true
                $(`#${page}-content`).html(data.pages[page])
            })

            setTimeout(loadNext, 200)
            return data
        }, function() {
            console.log("pages failed", pages)
            setTimeout(loadNext, 300)
        })
    }



    var tabs = '<div><ul id="tabs" class="nav nav-tabs tabs-top brief-tabs" data-tabs="tabs">'
    var content = '<div><div class="tab-content">'
    fullReport.root.forEach((id) => {
        const report = fullReport.children[id]
        const className = (report.id == window.location.hash.substr(1)) ? 'active': ''
        const rtype = (report.type == 'report')? report.id : ''
        tabs += `<li class="${className}"><a href="#${id}" data-toggle="tab" data-rtype="${rtype}">${report.name}</a></li>`

        content += `<div class="tab-pane ${className}" id="${id}"><h3>${report.name}</h3>`
        if (report.type == 'grouping') {
            var subtabs = '<div class="btn-group grouping" role="group">'
            var blah = '</div><div class="subtabContent">'
            report.children.forEach((cid) => {
                queueLoad(cid)
                const report = fullReport.children[cid]
                subtabs += `<button data-cid="${cid}" type="button" class="btn btn-default">${report.name}</button>`
                blah += `<div id="${cid}-content">${cid}</div>`
            })
            content += subtabs + blah + '</div>'
        } else {
            queueLoad(id)
            content += `<div id="${id}-content"></div>`
        }
        content += '</div>'
    })
    content += '</div></div>'
    tabs += '</ul></div>'

    target.append($(tabs)).append($(content))


    target.on('click', '.grouping button', function(e) {
        var elem = $(e.target)
        const cid = elem.data('cid')
        elem.siblings().addClass('btn-default').removeClass('btn-primary')
        elem.removeClass('btn-default').addClass('btn-primary')
        const c = $(`#${cid}-content`)
        c.show()
        c.siblings().hide()
        showReport(cid)
    })

    const navTabs = target.find('.nav-tabs')
    navTabs.stickyTabs() // TODO see if we can eliminate this soon
    navTabs.on('click', 'li a', function(e) {
        const elem = $(e.target)
        const cid = elem.data('rtype')
        if (cid) {
            showReport(cid)
        }
    })

    target.find('.grouping button:first-child').click()
    if (window.location.hash) {
        showReport(window.location.hash.substr(1))
    }

    loadNext()
}
