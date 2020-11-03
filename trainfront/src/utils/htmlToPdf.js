import html2Canvas from 'html2canvas'
import JsPDF from 'jspdf'


/**
 * @param  ele          要生成 pdf 的DOM元素（容器）
 * @param  padfName     PDF文件生成后的文件名字
 * */
export default {
  install(Vue, options) {
    let top = document.getElementById('maintop');
    if (top != null) {
      top.scrollIntoView();
      top = null;
    }
    Vue.prototype.getPdf = function (ele) {
      let url;
      html2Canvas(document.querySelector('#pdfDom'), {
        allowTaint: true,
        scale:2//提升画面质量
      }).then(function (canvas) {
          let contentWidth = canvas.width
          let contentHeight = canvas.height
          let pageData = canvas.toDataURL('image/jpeg', 1.0)
        // 设置pdf的尺寸，pdf要使用pt单位 已知 1pt/1px = 0.75   pt = (px/scale)* 0.75
        // 2为上面的scale 缩放了2倍
        var pdfX = (contentWidth + 10) / 2 * 0.75
        var pdfY = (contentHeight + 500) / 2 * 0.75 // 500为底部留白
          let PDF = new JsPDF('', 'pt', [pdfX, pdfY])
        // 设置内容图片的尺寸，img是pt单位
        var imgX = pdfX;
        var imgY = (contentHeight / 2 * 0.75); //内容图片这里不需要留白的距离
        PDF.addImage(pageData, 'jpeg', 0, 0, imgX, imgY);
          // if (leftHeight < pageHeight) {
          //   PDF.addImage(pageData, 'JPEG', 0, 0, imgWidth, imgHeight)
          // } else {//分页
          //   while (leftHeight > 0) {
          //     // PDF.addImage(pageData, 'JPEG', 0, position, imgWidth, imgHeight)
          //     leftHeight -= pageHeight
          //     position -= 841.89
          //     //避免添加空白页
          //     if (leftHeight > 0) {
          //       PDF.addPage()
          //     }
          //   }
          // }
          window.open(PDF.output('bloburl'))//新标签打开生成的pdf
        }
      )
    }
  }
}
