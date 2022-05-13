// eslint-disable-next-line import/extensions,import/no-unresolved
import mock from '@/@fake-db/mock'
/* eslint-disable global-require */
const data = {
  faqData: {
    product: {
      icon: 'FileTextIcon',
      title: 'Sản phẩm',
      subtitle: 'Những điều cần biết?',
      qandA: [
        {
          question: 'Tìm kiếm thông tin ở đâu?',
          ans:
            'Thông tin được đề cập trên trang chủ. Nếu bạn muốn biết thêm thông tin vui lòng liên hệ với chúng tôi!!!',
        },
        {
          question: 'Làm sao để đăng ký?',
          ans:
            'Sau khi bạn đăng ký qua website, chúng tôi sẽ duyệt thông tin và gửi cho bạn email xác nhận. Nếu bạn không nhận được email xác nhận, vui lòng liên hệ với chúng tôi!!!',
        },
      ],
    },
    // services
    services: {
      icon: 'SettingsIcon',
      title: 'Dịch vụ',
      subtitle: 'Những điều cần biết?',
      qandA: [
        {
          question: 'Tìm kiếm thông tin liên hệ ở đâu?',
          ans:
            'Thông tin được đề cập ngay cuối phần FAQ.',
        },
        {
          question: 'Cách liên hệ nhanh nhất?',
          ans:
            'Bạn có thể liên hệ trực tiếp với chúng tôi qua địa chỉ email hoặc gọi điện đến số hotline đã được đề cập!',
        },
      ],
    },
  },
}

mock.onGet('/faq/data').reply(config => {
  const { q = '' } = config.params
  const queryLowered = q.toLowerCase()

  const filteredData = {}

  Object.entries(data.faqData).forEach(entry => {
    const [categoryName, categoryObj] = entry
    // eslint-disable-next-line arrow-body-style
    const filteredQAndAOfCategory = categoryObj.qandA.filter(qAndAObj => {
      return qAndAObj.question.toLowerCase().includes(queryLowered)
    })
    if (filteredQAndAOfCategory.length) {
      filteredData[categoryName] = { ...categoryObj, qandA: filteredQAndAOfCategory }
    }
  })

  return [200, filteredData]
})
